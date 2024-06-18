<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Laracasts\Flash\Flash;
use \Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use MediaUploader;

class AdminMediaController extends Controller
{

    protected $fileInfo = [];
    protected $uploaded_file = '';
    protected $upload_path = '';
    protected $filename = '';


    public function __construct() {
        $this->upload_path = (string)'uploads/'.date('Ym');
    }


    /**
     * Display a listing of the Media.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $product_id = $request->get('product_id');

        $media = Media::latest('id')->paginate(12);
        $product_attachments = collect();

        if(!is_null($product_id)) {

            $p = Product::find($product_id);

            if (count($p->product_images)) {
              $product_attachments = collect($p->product_images)->pluck('id')->reverse();
              $ids = $product_attachments->implode('id', ', ');
              $media = Media::orderByRaw(\DB::raw("FIELD(id, {$ids}) DESC"))->paginate(12);
            }


        }

        return ($request->path() == 'ch-admin/mediabrowser') ?
            view('admin.media.browser', compact('media', 'product_attachments')) :
            view('admin.media.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');
        $disk = $request->has('disk') ? $request->input('disk') : 'public';

        $this->upload_path = ($disk == 'public') ? $this->upload_path : str_replace('uploads', 'local', $this->upload_path);

        try {
            $media = MediaUploader::fromSource($file)
                ->toDestination($disk, $this->upload_path)
                ->upload();
        } catch (\Exception $exception) {
            return response()->json(['errors' => $exception->getMessage()], 500);
        }


        if( $media->aggregate_type == 'image' && $disk == 'public' ) {
            foreach (config('media.sizes') as $name => $size) {

                $width = ($name != 'medium') ? $size[0] : null;
                $height = $size[1];

                $resized_filename = $media->filename . '.' . $media->extension;
                $abs_path = $media->directory . '/' . $name . '/';

                if (!File::exists(storage_path('app/'.$media->disk.'/'.$abs_path))) {
                    try {
                        File::makeDirectory(storage_path('app/'.$media->disk.'/'.$abs_path));
                    } catch (\Exception $exception) {
                        die($exception->getMessage());
                    }

                }

                Image::make(storage_path('app/'.$disk.'/'.$media->getDiskPath()))
                    ->resize($width, $height, function( $constraint ){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save(storage_path('app/'.$media->disk.'/'.$abs_path . $resized_filename));

                $media->meta()->updateOrCreate(['size_name' => $name], ['path' => $abs_path . $resized_filename]);
            }
        }

        if (\request('product_id') && $product = Product::find(\request('product_id'))) {
            $product->attachMedia($media, [$request->input('tag') ?? 'gallery']);
        }

        return response()->json($media->id, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $file = Media::findOrFail($id);

        if ( $request->ajax() ) {
            $response = '<div class="col-lg-4 text-right pull-right"><a href="'.route('ch-admin.media.edit', [$file->id]).'">Edit</a></div>
                            <div class="col-lg-6">
                                <img alt="" class="img-preview pull-left" src="'.$file->ThumbnailUrl.'">
                                <p class="name" data-dz-name>'.$file->filename.'</p>
                            </div><div class="clearfix"></div>';
            return $response;
        }

        return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $file = Media::findOrFail($id);
        $pageTitle = 'Edit : <span>'.$file->title.'</span>';

        return view('admin.media.edit', compact('file', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $formData = $request->all();

        if (is_image($media->mime_type) ) {
            $formData['media_meta'] = serialize(array_merge($media->media_meta, $formData['media_meta']));
        }

        $media->update($formData);

        Flash::success('Media updated successfully.');

        return redirect()->route('ch-admin.media.edit', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $media = Media::find($request->input('id'));
        if ( ! isset( $media->id ) ) {
            return response()->json('Unable to delete file.', 422);
        } else {

            if (!empty($media->media_meta)) {
                foreach ($media->media_meta['sizes'] as $key => $value) {
                    unlink($value['path']);
                }
            }

            if (\request('product_id') && $product = Product::find('product_id')) {
                $product->detachMedia($media);
            }

            $id = $media->id;

            $media->delete();

            return response()->json($id, 200);
        }
    }


    private function resizeImage() {

        $media_meta['sizes']['full'] = array(
            'file'      => pathinfo($this->uploaded_file)['basename'],
            'width'     => Image::make(public_upload_path($this->uploaded_file))->width(),
            'height'    => Image::make(public_upload_path($this->uploaded_file))->height()
        );

        foreach (config('media.sizes') as $name => $size) {

            $width = ($name != 'medium') ? $size[0] : null;
            $height = $size[1];

            $resized_filename = $this->getFilename('-'.$name);
            Image::make(public_upload_path($this->uploaded_file))
                                    ->resize($width, $height, function( $constraint ){
                                        $constraint->aspectRatio();
                                    })->save( public_upload_path($this->upload_path) . '/' . $resized_filename );

            $media_meta['sizes'][$name] = [
                'file' => $resized_filename,
                'width' => $width,
                'height' => $height
            ];
        }

        return $media_meta;

    }


    private function getFilename($resized='') {

        $filename = str_slug($this->fileInfo['filename']) . $resized . '.' . $this->fileInfo['extension'];

        $i = 1;
        while ( Storage::disk('uploads')->exists( $this->upload_path.'/'.$filename ) ) {
            $filename = str_slug($this->fileInfo['filename']) . '_' . $i . $resized . '.' . $this->fileInfo['extension'];
            $i++;
        }

        $this->filename = $filename;

        return $filename;

    }

}
