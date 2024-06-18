<div class="mb-3 row">
    <label for="settings[mail][driver]" class="col-sm-2 control-label">Driver</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][driver]" name="settings[mail][driver]"
               placeholder="smtp" value="{{ old('settings.mail.driver', setting('mail.driver', config('mail.driver'))) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[mail][host]" class="col-sm-2 control-label">SMTP Host Address</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][host]" name="settings[mail][host]"
               placeholder="" value="{{ old('settings.mail.host', setting('mail.host', config('mail.host'))) }}">
    </div>
</div>


<div class="mb-3 row">
    <label for="settings[mail][port]" class="col-sm-2 control-label">SMTP Host Port</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][port]" name="settings[mail][port]"
               placeholder="" value="{{ old('settings.mail.port', setting('mail.port', config('mail.port'))) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[mail][username]" class="col-sm-2 control-label">SMTP Server Username</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][username]" name="settings[mail][username]"
               placeholder="" value="{{ old('settings.mail.username', setting('mail.username', config('mail.username'))) }}">
    </div>
</div>


<div class="mb-3 row">
    <label for="settings[mail][password]" class="col-sm-2 control-label">SMTP Server Password</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][password]" name="settings[mail][password]"
               placeholder="" value="{{ old('settings.mail.password', setting('mail.password', config('mail.password'))) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[mail][encryption]" class="col-sm-2 control-label">E-Mail Encryption Protocol</label>
    <div class="col-sm-4">
        <select class="form-control select2" id="settings[mail][encryption]" name="settings[mail][encryption]">
            <option value="" @if ( old('settings.mail.encryption', setting('mail.encryption') ) == '' ) SELECTED @endif>None</option>
            <option value="tls" @if ( old('settings.mail.encryption', setting('mail.encryption') ) == 'tls' ) SELECTED @endif>TLS</option>
            <option value="ssl" @if ( old('settings.mail.encryption', setting('mail.encryption') ) == 'ssl' ) SELECTED @endif>SSL</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[mail][from][address]" class="col-sm-2 control-label">Mail From Address</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][from][address]" name="settings[mail][from][address]"
               placeholder="support@domain.com" value="{{ old('settings.mail.from.address', setting('mail.from.address', config('mail.from.address'))) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[mail][from][name]" class="col-sm-2 control-label">Mail From Name</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][from][name]" name="settings[mail][from][name]"
               placeholder="{{config('app.name')}}" value="{{ old('settings.mail.from.name', setting('mail.from.name', config('mail.from.name'))) }}">
    </div>
</div>
