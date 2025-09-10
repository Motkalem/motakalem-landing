@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>{{ $title }}</h4>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.settings.update-all') }}">
                        @csrf
                        
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                            @foreach($groupOrder as $groupKey => $groupLabel)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                            id="{{ $groupKey }}-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#{{ $groupKey }}" 
                                            type="button" 
                                            role="tab">
                                        {{ $groupLabel }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-4" id="settingsTabContent">
                            @foreach($groupOrder as $groupKey => $groupLabel)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                     id="{{ $groupKey }}" 
                                     role="tabpanel">
                                    
                                    <div class="row">
                                        @if(isset($settingsByGroup[$groupKey]))
                                            @if($groupKey === 'payment_buttons')
                                                <!-- Special layout for payment buttons -->
                                                <div class="col-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0">خيارات أزرار الدفع</h5>
                                                            <p class="text-muted small mb-0">اختر أنواع الدفع التي تريد إظهارها في الواجهة الأمامية</p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach($settingsByGroup[$groupKey] as $setting)
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" 
                                                                                   type="checkbox" 
                                                                                   role="switch" 
                                                                                   id="setting_{{ $setting->id }}" 
                                                                                   name="settings[{{ $setting->id }}][value]" 
                                                                                   value="1" 
                                                                                   {{ $setting->value == '1' || $setting->value == 'true' ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="setting_{{ $setting->id }}">
                                                                                <strong>{{ $setting->name }}</strong>
                                                                                <br>
                                                                                <small class="text-muted">{{ $setting->description }}</small>
                                                                            </label>
                                                                        </div>
                                                                        <input type="hidden" name="settings[{{ $setting->id }}][name]" value="{{ $setting->name }}">
                                                                        <input type="hidden" name="settings[{{ $setting->id }}][description]" value="{{ $setting->description }}">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="alert alert-info mt-3">
                                                                <i class="ti-info"></i>
                                                                <strong>ملاحظة:</strong> يمكنك تفعيل كلا الخيارين أو أحدهما فقط حسب احتياجاتك.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Regular layout for other groups -->
                                                @foreach($settingsByGroup[$groupKey] as $setting)
                                                    <div class="col-md-6 mb-4">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title">{{ $setting->name }}</h6>
                                                                <p class="card-text text-muted small">{{ $setting->description }}</p>
                                                                
                                                                <input type="hidden" name="settings[{{ $setting->id }}][name]" value="{{ $setting->name }}">
                                                                <input type="hidden" name="settings[{{ $setting->id }}][description]" value="{{ $setting->description }}">
                                                                
                                                                @if($setting->type === 'boolean')
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" 
                                                                               type="checkbox" 
                                                                               role="switch" 
                                                                               id="setting_{{ $setting->id }}" 
                                                                               name="settings[{{ $setting->id }}][value]" 
                                                                               value="1" 
                                                                               {{ $setting->value == '1' || $setting->value == 'true' ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="setting_{{ $setting->id }}">
                                                                            {{ $setting->value == '1' || $setting->value == 'true' ? __('Yes') : __('No') }}
                                                                        </label>
                                                                    </div>
                                                                @elseif($setting->type === 'textarea')
                                                                    <textarea name="settings[{{ $setting->id }}][value]" 
                                                                              class="form-control" 
                                                                              rows="3">{{ $setting->value }}</textarea>
                                                                @elseif($setting->type === 'number')
                                                                    <input name="settings[{{ $setting->id }}][value]" 
                                                                           type="number" 
                                                                           class="form-control" 
                                                                           value="{{ $setting->value }}" 
                                                                           step="any">
                                                                @elseif($setting->type === 'email')
                                                                    <input name="settings[{{ $setting->id }}][value]" 
                                                                           type="email" 
                                                                           class="form-control" 
                                                                           value="{{ $setting->value }}">
                                                                @elseif($setting->type === 'url')
                                                                    <input name="settings[{{ $setting->id }}][value]" 
                                                                           type="url" 
                                                                           class="form-control" 
                                                                           value="{{ $setting->value }}">
                                                                @else
                                                                    <input name="settings[{{ $setting->id }}][value]" 
                                                                           type="text" 
                                                                           class="form-control" 
                                                                           value="{{ $setting->value }}">
                                                                @endif
                                                                
                                                                <small class="text-muted">
                                                                    <code>{{ $setting->key }}</code> | 
                                                                    <span class="badge bg-secondary">{{ $setting->type }}</span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @else
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="ti-info"></i> لا توجد إعدادات في هذه المجموعة
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-end">
                            <button id="save-settings-btn" type="submit" class="btn btn-primary bg-primary btn-lg text-white">
                                <span class="btn-text"><i class="ti-save text-white"></i> {{ __('Save All Settings') }}</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="checkbox"][role="switch"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const label = this.nextElementSibling;
                
                if (!label.querySelector('strong')) {
                    label.textContent = this.checked ? '{{ __("Yes") }}' : '{{ __("No") }}';
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const onetimeCheckbox = document.querySelector('input[name="settings[{{ \App\Models\GeneralSetting::SHOW_ONETIME_PAYMENT }}][value]"]');
        const recurringCheckbox = document.querySelector('input[name="settings[{{ \App\Models\GeneralSetting::SHOW_RECURRING_PAYMENT }}][value]"]');
        const form = document.querySelector('form');

        if (onetimeCheckbox && recurringCheckbox && form) {
            function validateAtLeastOneChecked(e) {
                if (!onetimeCheckbox.checked && !recurringCheckbox.checked) {
                    e.preventDefault();
                    alert('{{ __("At least one payment button must be selected.") }}');
                    onetimeCheckbox.focus();
                    return false;
                }
                return true;
            }

            form.addEventListener('submit', validateAtLeastOneChecked);

            [onetimeCheckbox, recurringCheckbox].forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (!onetimeCheckbox.checked && !recurringCheckbox.checked) {
                        this.checked = true;
                        alert('{{ __("At least one payment button must be selected.") }}');
                    }
                });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const saveBtn = document.getElementById('save-settings-btn');
        if (saveBtn) {
            saveBtn.form && saveBtn.form.addEventListener('submit', function() {
                saveBtn.disabled = true;
                saveBtn.querySelector('.btn-text').classList.add('d-none');
                saveBtn.querySelector('.spinner-border').classList.remove('d-none');
            });
        }
    });

</script>
@endpush