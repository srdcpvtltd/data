<div class="mb-3 row">
    <label for="tos" class="col-sm-2 control-label">Terms of Service</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="tos" name="settings[tos]">{{ old('settings.tos', setting('tos')) }}</textarea>
    </div>
</div>

<div class="mb-3 row">
    <label for="privacy_policy" class="col-sm-2 control-label">Privacy Policy</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="privacy_policy" name="settings[privacy_policy]">{{ old('settings.privacy_policy', setting('privacy_policy')) }}</textarea>
    </div>
</div>

<div class="mb-3 row">
    <label for="refund_policy" class="col-sm-2 control-label">Refund Policy</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="refund_policy" name="settings[refund_policy]">{{ old('settings.refund_policy', setting('refund_policy')) }}</textarea>
    </div>
</div>

<div class="mb-3 row">
    <label for="contact_details" class="col-sm-2 control-label">Contact Details</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="contact_details" name="settings[contact_details]">{{ old('settings.contact_details', setting('contact_details')) }}</textarea>
    </div>
</div>
@push('head')
    <script src="{{url('assets/backend/js/vendors/ckeditor.js')}}"></script>
@endpush

@push('scripts')
    <script>
        const textAreas = ['#tos', '#privacy_policy', '#refund_policy', '#contact_details'];
        textAreas.forEach((el) => {
            ClassicEditor.create( document.querySelector( el ), {
                removePlugins: ['EasyImage', 'ImageUpload'],
            } );
        });
    </script>
@endpush
