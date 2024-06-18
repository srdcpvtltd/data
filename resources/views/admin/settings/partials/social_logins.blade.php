<div class="mb-3 row">
    <label for="settings[social_login][enabled]" class="col-sm-2 control-label">Enable Social Logins</label>
    <div class="col-sm-3">
        <select class="form-control" id="settings[social_login][enabled]" name="settings[social_login][enabled]">
            <option value="no" @if ( old('settings.social_login.enabled', setting('social_login.enabled')) == 'no' ) SELECTED @endif>No</option>
            <option value="yes" @if ( old('settings.social_login.enabled', setting('social_login.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
        </select>
    </div>
</div>

<h3 class="sub-settings">Facebook</h3>

<div class="mb-3 row">
    <label for="settings[services][facebook][enabled]" class="col-sm-2 control-label">Enable Facebook</label>
    <div class="col-sm-3">
        <select class="form-control" id="settings[services][facebook][enabled]" name="settings[services][facebook][enabled]">
            <option value="no" @if ( old('settings.services.facebook.enabled', setting('services.facebook.enabled')) == 'no' ) SELECTED @endif>No</option>
            <option value="yes" @if ( old('settings.services.facebook.enabled', setting('services.facebook.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[services][facebook][client_id]" class="col-sm-2 control-label">APP ID</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][facebook][client_id]" name="settings[services][facebook][client_id]"
               value="{{ old('settings.services.facebook.client_id', setting('services.facebook.client_id')) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[services][facebook][client_secret]" class="col-sm-2 control-label">APP Secret</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][facebook][client_secret]" name="settings[services][facebook][client_secret]"
               value="{{ old('settings.services.facebook.client_secret', setting('services.facebook.client_secret')) }}">
    </div>
</div>

<div class="mb-3 row">
    <label class="col-sm-2 control-label">Valid OAuth Redirect URIs</label>
    <div class="col-sm-6">
        <div class="form-control" readonly>{{site_url('/auth/facebook/callback')}}</div>
    </div>
</div>



<h3 class="sub-settings" style="display: none;">Twitter</h3>

<div class="mb-3 row" style="display: none;">
    <label for="settings[services][twitter][enabled]" class="col-sm-2 control-label">Enable Twitter</label>
    <div class="col-sm-3">
        <select class="form-control" id="settings[services][twitter][enabled]" name="settings[services][twitter][enabled]">
            <option value="no" @if ( old('settings.services.twitter.enabled', setting('services.twitter.enabled')) == 'no' ) SELECTED @endif>No</option>
            <option value="yes" @if ( old('settings.services.twitter.enabled', setting('services.twitter.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
        </select>
    </div>
</div>

<div class="mb-3 row" style="display: none;">
    <label for="settings[services][twitter][client_id]" class="col-sm-2 control-label">APP ID</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][twitter][client_id]" name="settings[services][twitter][client_id]"
               value="{{ old('settings.services.twitter.client_id', setting('services.twitter.client_id')) }}">
    </div>
</div>

<div class="mb-3 row" style="display: none;">
    <label for="settings[services][twitter][client_secret]" class="col-sm-2 control-label">APP Secret</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][twitter][client_secret]" name="settings[services][twitter][client_secret]"
               value="{{ old('settings.services.twitter.client_secret', setting('services.twitter.client_secret')) }}">
    </div>
</div>


<h3 class="sub-settings">Envato</h3>

<div class="mb-3 row">
    <label for="settings[services][envato][enabled]" class="col-sm-2 control-label">Enable Envato</label>
    <div class="col-sm-3">
        <select class="form-control" id="settings[services][envato][enabled]" name="settings[services][envato][enabled]">
            <option value="no" @if ( old('settings.services.envato.enabled', setting('services.envato.enabled')) == 'no' ) SELECTED @endif>No</option>
            <option value="yes" @if ( old('settings.services.envato.enabled', setting('services.envato.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[services][envato][client_id]" class="col-sm-2 control-label">APP ID</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][envato][client_id]" name="settings[services][envato][client_id]"
               value="{{ old('settings.services.envato.client_id', setting('services.envato.client_id')) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[services][envato][client_secret]" class="col-sm-2 control-label">APP Secret</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][envato][client_secret]" name="settings[services][envato][client_secret]"
               value="{{ old('settings.services.envato.client_secret', setting('services.envato.client_secret')) }}">
    </div>
</div>
