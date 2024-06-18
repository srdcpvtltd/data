<div class="mb-3 row">
    <label for="settings[header_code]" class="col-sm-2 control-label">Header Code</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="settings[header_code]" name="settings[header_code]">{{ old('settings.header_code', setting('header_code')) }}</textarea>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[footer_code]" class="col-sm-2 control-label">Footer Code</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="settings[footer_code]" name="settings[footer_code]">{{ old('settings.footer_code', setting('footer_code')) }}</textarea>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[custom_css]" class="col-sm-2 control-label">Custom CSS</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="settings[custom_css]" name="settings[custom_css]">{{ old('settings.custom_css', setting('custom_css')) }}</textarea>
    </div>
</div>
