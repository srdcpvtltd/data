<div class="mb-3 row">
    <label for="settings[app][name]" class="col-sm-2 control-label">Site Name</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[app][name]" name="settings[app][name]"
               placeholder="Site name" value="{{ old('settings.app.name', setting('app.name', config('app.name'))) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[app][logo]" class="col-sm-2 control-label">Site Logo</label>
    <div class="col-sm-4">
        <div class="uploader">
            <button
                id="logo-uplodaer"
                data-media-config='{"key": "settings[app][logo]", "container": ".site-logo", "single_upload": "true", "maxFiles": 1, "previewsContainer": ".site-logo-preview"}'
                data-files="@if (isset($logo) && !empty($logo)){{$logo}}@endif"
                type="button"
                class="btn btn-primary btn-sm text-white dropzone">
                <i class="ti-plus"></i> Upload Image
            </button>

            <div class="site-logo"></div>
            <div class="site-logo-preview mt-3"></div>
        </div>

    </div>
</div>

<div class="mb-3 row">
    <label for="settings[app][favicon]" class="col-sm-2 control-label">Favicon</label>
    <div class="col-sm-4">
        <div class="uploader">

            <button
                id="favicon-uploader"
                data-media-config='{"key": "settings[app][favicon]", "container": ".favicon-ctn", "single_upload": "true", "maxFiles": 1, "previewsContainer": ".favicon-preview"}'
                data-files="@if (isset($favicon) && !empty($favicon)){{$favicon}}@endif"
                type="button"
                class="btn btn-primary btn-sm text-white dropzone">
                <i class="ti-plus"></i> Upload Favicon
            </button>

            <div class="favicon-ctn"></div>
            <div class="favicon-preview mt-3"></div>
        </div>

    </div>
</div>

<div class="mb-3 row">
    <label for="settings[app][url]" class="col-sm-2 control-label">Site URL</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[app][url]" name="settings[app][url]"
               placeholder="Site URL" value="{{ old('settings.app.url', setting('app.url', config('app.url'))) }}">
    </div>
</div>



<div class="mb-3 row">
    <label for="settings[mail][from][address]" class="col-sm-2 control-label">Site Email</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[mail][from][address]" name="settings[mail][from][address]"
               placeholder="no-reply@email.com" value="{{ old('settings.mail.from.address', setting('mail.from.address', config('mail.from.address'))) }}">
    </div>
</div>

<h3 class="sub-settings">Currency</h3>

<div class="mb-3 row">
    <label for="settings[site_name]" class="col-sm-2 control-label">Currency</label>
    <div class="col-sm-4">

        <select class="form-control select2" name="settings[currency]">
            <option value="AED" @if ( old('settings.currency', setting('currency') ) == 'AED' ) SELECTED @endif>United Arab Emirates dirham (&#x62f;.&#x625;)</option>
            <option value="AFN" @if ( old('settings.currency', setting('currency') ) == 'AFN' ) SELECTED @endif>Afghan afghani (&#x60b;)</option>
            <option value="ALL" @if ( old('settings.currency', setting('currency') ) == 'ALL' ) SELECTED @endif>Albanian lek (L)</option>
            <option value="AMD" @if ( old('settings.currency', setting('currency') ) == 'AMD' ) SELECTED @endif>Armenian dram (AMD)</option>
            <option value="ANG" @if ( old('settings.currency', setting('currency') ) == 'ANG' ) SELECTED @endif>Netherlands Antillean guilder (&fnof;)</option>
            <option value="AOA" @if ( old('settings.currency', setting('currency') ) == 'AOA' ) SELECTED @endif>Angolan kwanza (Kz)</option>
            <option value="ARS" @if ( old('settings.currency', setting('currency') ) == 'ARS' ) SELECTED @endif>Argentine peso (&#036;)</option>
            <option value="AUD" @if ( old('settings.currency', setting('currency') ) == 'AUD' ) SELECTED @endif>Australian dollar (&#036;)</option>
            <option value="AWG" @if ( old('settings.currency', setting('currency') ) == 'AWG' ) SELECTED @endif>Aruban florin (Afl.)</option>
            <option value="AZN" @if ( old('settings.currency', setting('currency') ) == 'AZN' ) SELECTED @endif>Azerbaijani manat (AZN)</option>
            <option value="BAM" @if ( old('settings.currency', setting('currency') ) == 'BAM' ) SELECTED @endif>Bosnia and Herzegovina convertible mark (KM)</option>
            <option value="BBD" @if ( old('settings.currency', setting('currency') ) == 'BBD' ) SELECTED @endif>Barbadian dollar (&#036;)</option>
            <option value="BDT" @if ( old('settings.currency', setting('currency') ) == 'BDT' ) SELECTED @endif>Bangladeshi taka (&#2547;&nbsp;)</option>
            <option value="BGN" @if ( old('settings.currency', setting('currency') ) == 'BGN' ) SELECTED @endif>Bulgarian lev (&#1083;&#1074;.)</option>
            <option value="BHD" @if ( old('settings.currency', setting('currency') ) == 'BHD' ) SELECTED @endif>Bahraini dinar (.&#x62f;.&#x628;)</option>
            <option value="BIF" @if ( old('settings.currency', setting('currency') ) == 'BIF' ) SELECTED @endif>Burundian franc (Fr)</option>
            <option value="BMD" @if ( old('settings.currency', setting('currency') ) == 'BMD' ) SELECTED @endif>Bermudian dollar (&#036;)</option>
            <option value="BND" @if ( old('settings.currency', setting('currency') ) == 'BND' ) SELECTED @endif>Brunei dollar (&#036;)</option>
            <option value="BOB" @if ( old('settings.currency', setting('currency') ) == 'BOB' ) SELECTED @endif>Bolivian boliviano (Bs.)</option>
            <option value="BRL" @if ( old('settings.currency', setting('currency') ) == 'BRL' ) SELECTED @endif>Brazilian real (&#082;&#036;)</option>
            <option value="BSD" @if ( old('settings.currency', setting('currency') ) == 'BSD' ) SELECTED @endif>Bahamian dollar (&#036;)</option>
            <option value="BTC" @if ( old('settings.currency', setting('currency') ) == 'BTC' ) SELECTED @endif> Bitcoin (&#3647;) </option>
            <option value="BTN" @if ( old('settings.currency', setting('currency') ) == 'BTN' ) SELECTED @endif > Bhutanese ngultrum (Nu.) </option>
            <option value="BWP" @if ( old('settings.currency', setting('currency') ) == 'BWP' ) SELECTED @endif > Botswana pula (P) </option>
            <option value="BYR" @if ( old('settings.currency', setting('currency') ) == 'BYR' ) SELECTED @endif > Belarusian ruble (old) (Br) </option>
            <option value="BYN" @if ( old('settings.currency', setting('currency') ) == 'BYN' ) SELECTED @endif > Belarusian ruble (Br) </option>
            <option value="BZD" @if ( old('settings.currency', setting('currency') ) == 'BZD' ) SELECTED @endif > Belize dollar (&#036;) </option>
            <option value="CAD" @if ( old('settings.currency', setting('currency') ) == 'CAD' ) SELECTED @endif > Canadian dollar (&#036;) </option>
            <option value="CDF" @if ( old('settings.currency', setting('currency') ) == 'CDF' ) SELECTED @endif > Congolese franc (Fr) </option>
            <option value="CHF" @if ( old('settings.currency', setting('currency') ) == 'CHF' ) SELECTED @endif > Swiss franc (&#067;&#072;&#070;) </option>
            <option value="CLP" @if ( old('settings.currency', setting('currency') ) == 'CLP' ) SELECTED @endif > Chilean peso (&#036;) </option>
            <option value="CNY" @if ( old('settings.currency', setting('currency') ) == 'CNY' ) SELECTED @endif > Chinese yuan (&yen;) </option>
            <option value="COP" @if ( old('settings.currency', setting('currency') ) == 'COP' ) SELECTED @endif > Colombian peso (&#036;) </option>
            <option value="CRC" @if ( old('settings.currency', setting('currency') ) == 'CRC' ) SELECTED @endif > Costa Rican col&oacute;n (&#x20a1;) </option>
            <option value="CUC" @if ( old('settings.currency', setting('currency') ) == 'CUC' ) SELECTED @endif > Cuban convertible peso (&#036;) </option>
            <option value="CUP" @if ( old('settings.currency', setting('currency') ) == 'CUP' ) SELECTED @endif > Cuban peso (&#036;) </option>
            <option value="CVE" @if ( old('settings.currency', setting('currency') ) == 'CVE' ) SELECTED @endif > Cape Verdean escudo (&#036;) </option>
            <option value="CZK" @if ( old('settings.currency', setting('currency') ) == 'CZK' ) SELECTED @endif > Czech koruna (&#075;&#269;) </option>
            <option value="DJF" @if ( old('settings.currency', setting('currency') ) == 'DJF' ) SELECTED @endif > Djiboutian franc (Fr) </option>
            <option value="DKK" @if ( old('settings.currency', setting('currency') ) == 'DKK' ) SELECTED @endif > Danish krone (DKK) </option>
            <option value="DOP" @if ( old('settings.currency', setting('currency') ) == 'DOP' ) SELECTED @endif > Dominican peso (RD&#036;) </option>
            <option value="DZD" @if ( old('settings.currency', setting('currency') ) == 'DZD' ) SELECTED @endif > Algerian dinar (&#x62f;.&#x62c;) </option>
            <option value="EGP" @if ( old('settings.currency', setting('currency') ) == 'EGP' ) SELECTED @endif > Egyptian pound (EGP) </option>
            <option value="ERN" @if ( old('settings.currency', setting('currency') ) == 'ERN' ) SELECTED @endif > Eritrean nakfa (Nfk) </option>
            <option value="ETB" @if ( old('settings.currency', setting('currency') ) == 'ETB' ) SELECTED @endif > Ethiopian birr (Br) </option>
            <option value="EUR" @if ( old('settings.currency', setting('currency') ) == 'EUR' ) SELECTED @endif > Euro (&euro;) </option>
            <option value="FJD" @if ( old('settings.currency', setting('currency') ) == 'FJD' ) SELECTED @endif > Fijian dollar (&#036;) </option>
            <option value="FKP" @if ( old('settings.currency', setting('currency') ) == 'FKP' ) SELECTED @endif > Falkland Islands pound (&pound;) </option>
            <option value="GBP" @if ( old('settings.currency', setting('currency') ) == 'GBP' ) SELECTED @endif > Pound sterling (&pound;) </option>
            <option value="GEL" @if ( old('settings.currency', setting('currency') ) == 'GEL' ) SELECTED @endif > Georgian lari (&#x10da;) </option>
            <option value="GGP" @if ( old('settings.currency', setting('currency') ) == 'GGP' ) SELECTED @endif > Guernsey pound (&pound;) </option>
            <option value="GHS" @if ( old('settings.currency', setting('currency') ) == 'GHS' ) SELECTED @endif > Ghana cedi (&#x20b5;) </option>
            <option value="GIP" @if ( old('settings.currency', setting('currency') ) == 'GIP' ) SELECTED @endif > Gibraltar pound (&pound;) </option>
            <option value="GMD" @if ( old('settings.currency', setting('currency') ) == 'GMD' ) SELECTED @endif > Gambian dalasi (D) </option>
            <option value="GNF" @if ( old('settings.currency', setting('currency') ) == 'GNF' ) SELECTED @endif > Guinean franc (Fr) </option>
            <option value="GTQ" @if ( old('settings.currency', setting('currency') ) == 'GTQ' ) SELECTED @endif > Guatemalan quetzal (Q) </option>
            <option value="GYD" @if ( old('settings.currency', setting('currency') ) == 'GYD' ) SELECTED @endif > Guyanese dollar (&#036;) </option>
            <option value="HKD" @if ( old('settings.currency', setting('currency') ) == 'HKD' ) SELECTED @endif > Hong Kong dollar (&#036;) </option>
            <option value="HNL" @if ( old('settings.currency', setting('currency') ) == 'HNL' ) SELECTED @endif > Honduran lempira (L) </option>
            <option value="HRK" @if ( old('settings.currency', setting('currency') ) == 'HRK' ) SELECTED @endif > Croatian kuna (Kn) </option>
            <option value="HTG" @if ( old('settings.currency', setting('currency') ) == 'HTG' ) SELECTED @endif > Haitian gourde (G) </option>
            <option value="HUF" @if ( old('settings.currency', setting('currency') ) == 'HUF' ) SELECTED @endif > Hungarian forint (&#070;&#116;) </option>
            <option value="IDR" @if ( old('settings.currency', setting('currency') ) == 'IDR' ) SELECTED @endif > Indonesian rupiah (Rp) </option>
            <option value="ILS" @if ( old('settings.currency', setting('currency') ) == 'ILS' ) SELECTED @endif > Israeli new shekel (&#8362;) </option>
            <option value="IMP" @if ( old('settings.currency', setting('currency') ) == 'IMP' ) SELECTED @endif > Manx pound (&pound;) </option>
            <option value="INR" @if ( old('settings.currency', setting('currency') ) == 'INR' ) SELECTED @endif > Indian rupee (&#8377;) </option>
            <option value="IQD" @if ( old('settings.currency', setting('currency') ) == 'IQD' ) SELECTED @endif > Iraqi dinar (&#x639;.&#x62f;) </option>
            <option value="IRR" @if ( old('settings.currency', setting('currency') ) == 'IRR' ) SELECTED @endif > Iranian rial (&#xfdfc;) </option>
            <option value="IRT" @if ( old('settings.currency', setting('currency') ) == 'IRT' ) SELECTED @endif > Iranian toman (&#x62A;&#x648;&#x645;&#x627;&#x646;) </option>
            <option value="ISK" @if ( old('settings.currency', setting('currency') ) == 'ISK' ) SELECTED @endif > Icelandic kr&oacute;na (kr.) </option>
            <option value="JEP" @if ( old('settings.currency', setting('currency') ) == 'JEP' ) SELECTED @endif > Jersey pound (&pound;) </option>
            <option value="JMD" @if ( old('settings.currency', setting('currency') ) == 'JMD' ) SELECTED @endif > Jamaican dollar (&#036;) </option>
            <option value="JOD" @if ( old('settings.currency', setting('currency') ) == 'JOD' ) SELECTED @endif > Jordanian dinar (&#x62f;.&#x627;) </option>
            <option value="JPY" @if ( old('settings.currency', setting('currency') ) == 'JPY' ) SELECTED @endif > Japanese yen (&yen;) </option>
            <option value="KES" @if ( old('settings.currency', setting('currency') ) == 'KES' ) SELECTED @endif > Kenyan shilling (KSh) </option>
            <option value="KGS" @if ( old('settings.currency', setting('currency') ) == 'KGS' ) SELECTED @endif > Kyrgyzstani som (&#x441;&#x43e;&#x43c;) </option>
            <option value="KHR" @if ( old('settings.currency', setting('currency') ) == 'KHR' ) SELECTED @endif > Cambodian riel (&#x17db;) </option>
            <option value="KMF" @if ( old('settings.currency', setting('currency') ) == 'KMF' ) SELECTED @endif > Comorian franc (Fr) </option>
            <option value="KPW" @if ( old('settings.currency', setting('currency') ) == 'KPW' ) SELECTED @endif > North Korean won (&#x20a9;) </option>
            <option value="KRW" @if ( old('settings.currency', setting('currency') ) == 'KRW' ) SELECTED @endif > South Korean won (&#8361;) </option>
            <option value="KWD" @if ( old('settings.currency', setting('currency') ) == 'KWD' ) SELECTED @endif > Kuwaiti dinar (&#x62f;.&#x643;) </option>
            <option value="KYD" @if ( old('settings.currency', setting('currency') ) == 'KYD' ) SELECTED @endif > Cayman Islands dollar (&#036;) </option>
            <option value="KZT" @if ( old('settings.currency', setting('currency') ) == 'KZT' ) SELECTED @endif > Kazakhstani tenge (KZT) </option>
            <option value="LAK" @if ( old('settings.currency', setting('currency') ) == 'LAK' ) SELECTED @endif > Lao kip (&#8365;) </option>
            <option value="LBP" @if ( old('settings.currency', setting('currency') ) == 'LBP' ) SELECTED @endif > Lebanese pound (&#x644;.&#x644;) </option>
            <option value="LKR" @if ( old('settings.currency', setting('currency') ) == 'LKR' ) SELECTED @endif > Sri Lankan rupee (&#xdbb;&#xdd4;) </option>
            <option value="LRD" @if ( old('settings.currency', setting('currency') ) == 'LRD' ) SELECTED @endif > Liberian dollar (&#036;) </option>
            <option value="LSL" @if ( old('settings.currency', setting('currency') ) == 'LSL' ) SELECTED @endif > Lesotho loti (L) </option>
            <option value="LYD" @if ( old('settings.currency', setting('currency') ) == 'LYD' ) SELECTED @endif > Libyan dinar (&#x644;.&#x62f;) </option>
            <option value="MAD" @if ( old('settings.currency', setting('currency') ) == 'MAD' ) SELECTED @endif > Moroccan dirham (&#x62f;.&#x645;.) </option>
            <option value="MDL" @if ( old('settings.currency', setting('currency') ) == 'MDL' ) SELECTED @endif > Moldovan leu (MDL) </option>
            <option value="MGA" @if ( old('settings.currency', setting('currency') ) == 'MGA' ) SELECTED @endif > Malagasy ariary (Ar) </option>
            <option value="MKD" @if ( old('settings.currency', setting('currency') ) == 'MKD' ) SELECTED @endif > Macedonian denar (&#x434;&#x435;&#x43d;) </option>
            <option value="MMK" @if ( old('settings.currency', setting('currency') ) == 'MMK' ) SELECTED @endif > Burmese kyat (Ks) </option>
            <option value="MNT" @if ( old('settings.currency', setting('currency') ) == 'MNT' ) SELECTED @endif > Mongolian t&ouml;gr&ouml;g (&#x20ae;) </option>
            <option value="MOP" @if ( old('settings.currency', setting('currency') ) == 'MOP' ) SELECTED @endif > Macanese pataca (P) </option>
            <option value="MRO" @if ( old('settings.currency', setting('currency') ) == 'MRO' ) SELECTED @endif > Mauritanian ouguiya (UM) </option>
            <option value="MUR" @if ( old('settings.currency', setting('currency') ) == 'MUR' ) SELECTED @endif > Mauritian rupee (&#x20a8;) </option>
            <option value="MVR" @if ( old('settings.currency', setting('currency') ) == 'MVR' ) SELECTED @endif > Maldivian rufiyaa (.&#x783;) </option>
            <option value="MWK" @if ( old('settings.currency', setting('currency') ) == 'MWK' ) SELECTED @endif > Malawian kwacha (MK) </option>
            <option value="MXN" @if ( old('settings.currency', setting('currency') ) == 'MXN' ) SELECTED @endif > Mexican peso (&#036;) </option>
            <option value="MYR" @if ( old('settings.currency', setting('currency') ) == 'MYR' ) SELECTED @endif > Malaysian ringgit (&#082;&#077;) </option>
            <option value="MZN" @if ( old('settings.currency', setting('currency') ) == 'MZN' ) SELECTED @endif > Mozambican metical (MT) </option>
            <option value="NAD" @if ( old('settings.currency', setting('currency') ) == 'NAD' ) SELECTED @endif > Namibian dollar (&#036;) </option>
            <option value="NGN" @if ( old('settings.currency', setting('currency') ) == 'NGN' ) SELECTED @endif > Nigerian naira (&#8358;) </option>
            <option value="NIO" @if ( old('settings.currency', setting('currency') ) == 'NIO' ) SELECTED @endif > Nicaraguan c&oacute;rdoba (C&#036;) </option>
            <option value="NOK" @if ( old('settings.currency', setting('currency') ) == 'NOK' ) SELECTED @endif > Norwegian krone (&#107;&#114;) </option>
            <option value="NPR" @if ( old('settings.currency', setting('currency') ) == 'NPR' ) SELECTED @endif > Nepalese rupee (&#8360;) </option>
            <option value="NZD" @if ( old('settings.currency', setting('currency') ) == 'NZD' ) SELECTED @endif > New Zealand dollar (&#036;) </option>
            <option value="OMR" @if ( old('settings.currency', setting('currency') ) == 'OMR' ) SELECTED @endif > Omani rial (&#x631;.&#x639;.) </option>
            <option value="PAB" @if ( old('settings.currency', setting('currency') ) == 'PAB' ) SELECTED @endif > Panamanian balboa (B/.) </option>
            <option value="PEN" @if ( old('settings.currency', setting('currency') ) == 'PEN' ) SELECTED @endif > Peruvian nuevo sol (S/.) </option>
            <option value="PGK" @if ( old('settings.currency', setting('currency') ) == 'PGK' ) SELECTED @endif > Papua New Guinean kina (K) </option>
            <option value="PHP" @if ( old('settings.currency', setting('currency') ) == 'PHP' ) SELECTED @endif > Philippine peso (&#8369;) </option>
            <option value="PKR" @if ( old('settings.currency', setting('currency') ) == 'PKR' ) SELECTED @endif > Pakistani rupee (&#8360;) </option>
            <option value="PLN" @if ( old('settings.currency', setting('currency') ) == 'PLN' ) SELECTED @endif > Polish z&#x142;oty (&#122;&#322;) </option>
            <option value="PRB" @if ( old('settings.currency', setting('currency') ) == 'PRB' ) SELECTED @endif > Transnistrian ruble (&#x440;.) </option>
            <option value="PYG" @if ( old('settings.currency', setting('currency') ) == 'PYG' ) SELECTED @endif > Paraguayan guaran&iacute; (&#8370;) </option>
            <option value="QAR" @if ( old('settings.currency', setting('currency') ) == 'QAR' ) SELECTED @endif > Qatari riyal (&#x631;.&#x642;) </option>
            <option value="RON" @if ( old('settings.currency', setting('currency') ) == 'RON' ) SELECTED @endif > Romanian leu (lei) </option>
            <option value="RSD" @if ( old('settings.currency', setting('currency') ) == 'RSD' ) SELECTED @endif > Serbian dinar (&#x434;&#x438;&#x43d;.) </option>
            <option value="RUB" @if ( old('settings.currency', setting('currency') ) == 'RUB' ) SELECTED @endif > Russian ruble (&#8381;) </option>
            <option value="RWF" @if ( old('settings.currency', setting('currency') ) == 'RWF' ) SELECTED @endif > Rwandan franc (Fr) </option>
            <option value="SAR" @if ( old('settings.currency', setting('currency') ) == 'SAR' ) SELECTED @endif > Saudi riyal (&#x631;.&#x633;) </option>
            <option value="SBD" @if ( old('settings.currency', setting('currency') ) == 'SBD' ) SELECTED @endif > Solomon Islands dollar (&#036;) </option>
            <option value="SCR" @if ( old('settings.currency', setting('currency') ) == 'SCR' ) SELECTED @endif > Seychellois rupee (&#x20a8;) </option>
            <option value="SDG" @if ( old('settings.currency', setting('currency') ) == 'SDG' ) SELECTED @endif > Sudanese pound (&#x62c;.&#x633;.) </option>
            <option value="SEK" @if ( old('settings.currency', setting('currency') ) == 'SEK' ) SELECTED @endif > Swedish krona (&#107;&#114;) </option>
            <option value="SGD" @if ( old('settings.currency', setting('currency') ) == 'SGD' ) SELECTED @endif > Singapore dollar (&#036;) </option>
            <option value="SHP" @if ( old('settings.currency', setting('currency') ) == 'SHP' ) SELECTED @endif > Saint Helena pound (&pound;) </option>
            <option value="SLL" @if ( old('settings.currency', setting('currency') ) == 'SLL' ) SELECTED @endif > Sierra Leonean leone (Le) </option>
            <option value="SOS" @if ( old('settings.currency', setting('currency') ) == 'SOS' ) SELECTED @endif > Somali shilling (Sh) </option>
            <option value="SRD" @if ( old('settings.currency', setting('currency') ) == 'SRD' ) SELECTED @endif > Surinamese dollar (&#036;) </option>
            <option value="SSP" @if ( old('settings.currency', setting('currency') ) == 'SSP' ) SELECTED @endif > South Sudanese pound (&pound;) </option>
            <option value="STD" @if ( old('settings.currency', setting('currency') ) == 'STD' ) SELECTED @endif > S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra (Db) </option>
            <option value="SYP" @if ( old('settings.currency', setting('currency') ) == 'SYP' ) SELECTED @endif > Syrian pound (&#x644;.&#x633;) </option>
            <option value="SZL" @if ( old('settings.currency', setting('currency') ) == 'SZL' ) SELECTED @endif > Swazi lilangeni (L) </option>
            <option value="THB" @if ( old('settings.currency', setting('currency') ) == 'THB' ) SELECTED @endif > Thai baht (&#3647;) </option>
            <option value="TJS" @if ( old('settings.currency', setting('currency') ) == 'TJS' ) SELECTED @endif > Tajikistani somoni (&#x405;&#x41c;) </option>
            <option value="TMT" @if ( old('settings.currency', setting('currency') ) == 'TMT' ) SELECTED @endif > Turkmenistan manat (m) </option>
            <option value="TND" @if ( old('settings.currency', setting('currency') ) == 'TND' ) SELECTED @endif > Tunisian dinar (&#x62f;.&#x62a;) </option>
            <option value="TOP" @if ( old('settings.currency', setting('currency') ) == 'TOP' ) SELECTED @endif > Tongan pa&#x2bb;anga (T&#036;) </option>
            <option value="TRY" @if ( old('settings.currency', setting('currency') ) == 'TRY' ) SELECTED @endif > Turkish lira (&#8378;) </option>
            <option value="TTD" @if ( old('settings.currency', setting('currency') ) == 'TTD' ) SELECTED @endif > Trinidad and Tobago dollar (&#036;) </option>
            <option value="TWD" @if ( old('settings.currency', setting('currency') ) == 'TWD' ) SELECTED @endif > New Taiwan dollar (&#078;&#084;&#036;) </option>
            <option value="TZS" @if ( old('settings.currency', setting('currency') ) == 'TZS' ) SELECTED @endif > Tanzanian shilling (Sh) </option>
            <option value="UAH" @if ( old('settings.currency', setting('currency') ) == 'UAH' ) SELECTED @endif > Ukrainian hryvnia (&#8372;) </option>
            <option value="UGX" @if ( old('settings.currency', setting('currency') ) == 'UGX' ) SELECTED @endif > Ugandan shilling (UGX) </option>
            <option value="USD" @if ( old('settings.currency', setting('currency') ) == 'USD' ) SELECTED @endif > United States dollar (&#036;) </option>
            <option value="UYU" @if ( old('settings.currency', setting('currency') ) == 'UYU' ) SELECTED @endif > Uruguayan peso (&#036;) </option>
            <option value="UZS" @if ( old('settings.currency', setting('currency') ) == 'UZS' ) SELECTED @endif> Uzbekistani som (UZS) </option>
            <option value="VEF" @if ( old('settings.currency', setting('currency') ) == 'VEF' ) SELECTED @endif> Venezuelan bol&iacute;var (Bs F) </option>
            <option value="VND" @if ( old('settings.currency', setting('currency') ) == 'VND' ) SELECTED @endif> Vietnamese &#x111;&#x1ed3;ng (&#8363;) </option>
            <option value="VUV" @if ( old('settings.currency', setting('currency') ) == 'VUV' ) SELECTED @endif> Vanuatu vatu (Vt) </option>
            <option value="WST" @if ( old('settings.currency', setting('currency') ) == 'WST' ) SELECTED @endif> Samoan t&#x101;l&#x101; (T) </option>
            <option value="XAF" @if ( old('settings.currency', setting('currency') ) == 'XAF' ) SELECTED @endif> Central African CFA franc (CFA) </option>
            <option value="XCD" @if ( old('settings.currency', setting('currency') ) == 'XCD' ) SELECTED @endif> East Caribbean dollar (&#036;) </option>
            <option value="XOF" @if ( old('settings.currency', setting('currency') ) == 'XOF' ) SELECTED @endif> West African CFA franc (CFA) </option>
            <option value="XPF" @if ( old('settings.currency', setting('currency') ) == 'XPF' ) SELECTED @endif> CFP franc (Fr) </option>
            <option value="YER" @if ( old('settings.currency', setting('currency') ) == 'YER' ) SELECTED @endif> Yemeni rial (&#xfdfc;) </option>
            <option value="ZAR" @if ( old('settings.currency', setting('currency') ) == 'ZAR' ) SELECTED @endif> South African rand (&#082;) </option>
            <option value="ZMW" @if ( old('settings.currency', setting('currency') ) == 'ZMW' ) SELECTED @endif> Zambian kwacha (ZK) </option>
        </select>

    </div>
</div>

<div class="mb-3 row">
    <label for="settings[price_format]" class="col-sm-2 control-label">Price Format</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[price_format]" name="settings[price_format]">
            <option value="1" @if ( old('settings.price_format', setting('price_format') ) == '1' ) SELECTED @endif>Price with Symbol ($)</option>
            <option value="2" @if ( old('settings.price_format', setting('price_format') ) == '2' ) SELECTED @endif>Price with Currency Code (USD)</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[currency_position]" class="col-sm-2 control-label">Currency Position</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[currency_position]" name="settings[currency_position]">
            <option value="left" @if ( old('settings.site_name', setting('currency_position') ) == 'left' ) SELECTED @endif>Left</option>
            <option value="right" @if ( old('settings.site_name', setting('currency_position') ) == 'right' ) SELECTED @endif>Right</option>
            <option value="left_space" @if ( old('settings.site_name', setting('currency_position') ) == 'left_space' ) SELECTED @endif>Left with space</option>
            <option value="right_space" @if ( old('settings.site_name', setting('currency_position') ) == 'right_space' ) SELECTED @endif>Right with space</option>
        </select>
    </div>
</div>

<h3 class="sub-settings">Home Page</h3>

<div class="mb-3 row">
    <label for="settings[services][per_page]" class="col-sm-2 control-label">Services Per page</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[services][per_page]" name="settings[services][per_page]"
               placeholder="8" value="{{ old('settings.services.per_page', setting('services.per_page', 8)) }}">
    </div>
</div>



<h3 class="sub-settings">Registration</h3>
<div class="mb-3 row">
    <label for="settings[email_verification]" class="col-sm-2 control-label">Email Verification</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[email_verification]" name="settings[email_verification]">
            <option value="on" @if ( old('settings.email_verification', setting('email_verification') ) == 'on' ) SELECTED @endif>On</option>
            <option value="off" @if ( old('settings.email_verification', setting('email_verification') ) == 'off' ) SELECTED @endif>Off</option>
        </select>
    </div>
</div>

<h3 class="sub-settings">Make Site Private</h3>
<div class="mb-3 row">
    <label for="settings[make_site_private]" class="col-sm-2 control-label">Make Site Private</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[make_site_private]" name="settings[make_site_private]">
            <option value="off" @if ( old('settings.make_site_private', setting('make_site_private') ) == 'off' ) SELECTED @endif>Off</option>
            <option value="on" @if ( old('settings.make_site_private', setting('make_site_private') ) == 'on' ) SELECTED @endif>On</option>
        </select>
    </div>
</div>

<h3 class="sub-settings">ReCaptcha</h3>

<div class="mb-3 row">
    <label for="settings[recaptcha][enabled]" class="col-sm-2 control-label">ReCaptcha Validation</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[recaptcha][enabled]" name="settings[recaptcha][enabled]">
            <option value="off" @if ( old('settings.recaptcha.enabled', setting('recaptcha.enabled') ) == 'off' ) SELECTED @endif>Off</option>
            <option value="on" @if ( old('settings.recaptcha.enabled', setting('recaptcha.enabled') ) == 'on' ) SELECTED @endif>On</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[recaptcha][api_site_key]" class="col-sm-2 control-label">ReCaptcha Site key</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[recaptcha][api_site_key]" name="settings[recaptcha][api_site_key]" value="{{ old('settings.recaptcha.api_site_key', setting('recaptcha.api_site_key')) }}">
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[recaptcha][api_secret_key]" class="col-sm-2 control-label">ReCaptcha Secret key</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="settings[recaptcha][api_secret_key]" name="settings[recaptcha][api_secret_key]" value="{{ old('settings.recaptcha.api_secret_key', setting('recaptcha.api_secret_key')) }}">
    </div>
</div>

@push('scripts')
    @include('admin.media.upload')
    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/backend/js/media.js') }}"></script>
@endpush
