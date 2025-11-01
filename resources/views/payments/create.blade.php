@extends('layouts.app')

@push('styles')
<style>
    body { background:#f4f6fb; }
    .page-wrapper-bg { background:linear-gradient(180deg,#f4f6fb 0%,#eef2f9 100%); min-height:100%; }

    .payment-create-hero { background: linear-gradient(135deg,#667eea 0%,#764ba2 50%,#a855f7 100%); position:relative; overflow:hidden; border-bottom:6px solid rgba(255,255,255,0.3); }
    .payment-create-hero .floating-circle { position:absolute; border-radius:50%; background:rgba(255,255,255,0.12); animation: floatCreate 20s linear infinite; }
    .payment-create-hero .floating-circle:nth-child(1){ width:120px; height:120px; left:12%; animation-delay:0s; }
    .payment-create-hero .floating-circle:nth-child(2){ width:170px; height:170px; left:78%; animation-delay:4s; }
    .payment-create-hero .floating-circle:nth-child(3){ width:90px; height:90px; left:48%; animation-delay:8s; }
    @keyframes floatCreate { 0%{ transform:translateY(90vh) rotate(0deg); opacity:0;} 10%{opacity:1;} 90%{opacity:1;} 100%{ transform:translateY(-120px) rotate(360deg); opacity:0;} }

    .create-glass-card { background:linear-gradient(135deg,rgba(255,255,255,0.16),rgba(255,255,255,0.06)); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.32); border-radius:26px; position:relative; overflow:hidden; }
    .create-glass-card:before { content:""; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#667eea,#764ba2,#a855f7,#667eea); background-size:300% 100%; animation:gradientShift 6s ease infinite; }
    @keyframes gradientShift {0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;}}

    .fieldset-box { background:rgba(255,255,255,0.14); border:1px solid rgba(255,255,255,0.35); border-radius:18px; padding:1.25rem 1.25rem .85rem; position:relative; }
    .fieldset-box legend { font-size:.8rem; letter-spacing:.5px; font-weight:600; padding:0 .65rem; background:rgba(255,255,255,0.28); border-radius:12px; backdrop-filter:blur(4px); text-transform:uppercase; }
    .form-label { font-weight:600; font-size:.75rem; text-transform:uppercase; letter-spacing:.55px; color:#f1f5f9; }
    .form-control, .form-select { border-radius:14px; background:rgba(255,255,255,0.92); border:1px solid #d1d5db; }
    .form-control:focus, .form-select:focus { border-color:#764ba2; box-shadow:0 0 0 .15rem rgba(118,75,162,0.25); }
    .action-btn-gradient { background:linear-gradient(90deg,#667eea,#764ba2,#a855f7); border:none; color:#fff; font-weight:600; letter-spacing:.5px; box-shadow:0 8px 24px -6px rgba(103,65,148,.45); }
    .action-btn-gradient:hover { transform:translateY(-3px); box-shadow:0 14px 30px -4px rgba(103,65,148,.55); color:#fff; }
    .btn-outline-lite { background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.4); }
    .btn-outline-lite:hover { background:rgba(255,255,255,0.25); color:#fff; }
    .status-preview-badge { padding:.45rem .85rem; border-radius:30px; font-size:.7rem; font-weight:600; letter-spacing:.5px; display:inline-flex; align-items:center; gap:.35rem; }
    .status-badge-pending { background:#fff7ed; border:1px solid #fdba74; color:#c2410c; }
    .status-badge-paid { background:#ecfdf5; border:1px solid #6ee7b7; color:#047857; }
    .status-badge-overdue { background:#fef2f2; border:1px solid #fca5a5; color:#b91c1c; }
    .helper-text { font-size:.6rem; letter-spacing:.4px; color:#e2e8f0; text-transform:uppercase; }
    .paid-dependent { transition:.35s ease; }
    @media (max-width: 768px){ .create-glass-card { border-radius:22px; } }
</style>
@endpush

@section('content')
<div class="page-wrapper-bg">
<div class="payment-create-hero py-5">
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9 text-center mb-4">
                <div class="mx-auto mb-4" style="width:120px;height:120px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.18);border:3px solid rgba(255,255,255,0.4);backdrop-filter:blur(10px);">
                    <i class="fas fa-plus-circle text-white" style="font-size:3rem;"></i>
                </div>
                <h1 class="text-white fw-bold mb-2" style="letter-spacing:-1px;">Nuevo Pago</h1>
                <p class="text-white-50 mb-0">Registra una nueva obligación de pago</p>
            </div>
            <div class="col-lg-9">
                <div class="create-glass-card p-4 p-md-5">
                    <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
                        @csrf
                        <fieldset class="fieldset-box mb-4">
                            <legend>Datos Básicos</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Título *</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                                    @error('title')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Monto *</label>
                                    <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" required>
                                    @error('amount')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Categoría *</label>
                                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="" disabled selected>Seleccionar</option>
                                        @foreach($categories as $key => $label)
                                            <option value="{{ $key }}" @selected(old('category')===$key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Vencimiento *</label>
                                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control @error('due_date') is-invalid @enderror" required>
                                    @error('due_date')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-flex justify-content-between align-items-center">Estado <span class="helper-text">Vista previa</span></label>
                                    <select name="status" class="form-select status-select @error('status') is-invalid @enderror" id="status-select" >
                                        <option value="">Auto</option>
                                        <option value="pending" @selected(old('status')==='pending')>Pendiente</option>
                                        <option value="paid" @selected(old('status')==='paid')>Pagado</option>
                                        <option value="overdue" @selected(old('status')==='overdue')>Vencido</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                    <div class="mt-2" id="status-preview"></div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="fieldset-box mb-4">
                            <legend>Información de Pago</legend>
                            <div class="row g-3">
                                <div class="col-md-3 paid-dependent @if(old('status')!=='paid') d-none @endif">
                                    <label class="form-label">Fecha de Pago</label>
                                    <input type="date" name="paid_date" value="{{ old('paid_date') }}" class="form-control @error('paid_date') is-invalid @enderror">
                                    @error('paid_date')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 paid-dependent @if(old('status')!=='paid') d-none @endif">
                                    <label class="form-label">Método</label>
                                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                                        <option value="" selected>Seleccione</option>
                                        <option value="cash" @selected(old('payment_method')==='cash')>Efectivo</option>
                                        <option value="card" @selected(old('payment_method')==='card')>Tarjeta</option>
                                        <option value="transfer" @selected(old('payment_method')==='transfer')>Transferencia</option>
                                        <option value="online" @selected(old('payment_method')==='online')>En Línea</option>
                                    </select>
                                    @error('payment_method')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 paid-dependent @if(old('status')!=='paid') d-none @endif">
                                    <label class="form-label">Referencia</label>
                                    <input type="text" name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror" placeholder="N° transacción / comprobante">
                                    @error('reference')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Notas</label>
                                    <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Detalles adicionales">{{ old('notes') }}</textarea>
                                    @error('notes')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn action-btn-gradient"><i class="fas fa-save me-1"></i> Guardar</button>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-lite"><i class="fas fa-arrow-left me-1"></i> Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const statusSelect = document.getElementById('status-select');
    const paidDependent = document.querySelectorAll('.paid-dependent');
    const preview = document.getElementById('status-preview');

    function renderStatusBadge(val){
        if(!preview) return;
        const map = {
            pending: { cls: 'status-badge-pending', icon:'fa-hourglass-half', label:'Pendiente' },
            paid: { cls: 'status-badge-paid', icon:'fa-check-circle', label:'Pagado' },
            overdue: { cls: 'status-badge-overdue', icon:'fa-exclamation-triangle', label:'Vencido' }
        };
        const d = map[val];
        preview.innerHTML = d ? `<span class="status-preview-badge ${d.cls}"><i class="fas ${d.icon}"></i>${d.label}</span>` : '';
    }

    function togglePaidFields() {
        if (statusSelect && statusSelect.value === 'paid') {
            paidDependent.forEach(el => el.classList.remove('d-none'));
        } else {
            paidDependent.forEach(el => el.classList.add('d-none'));
        }
    }

    if(statusSelect){
        statusSelect.addEventListener('change', ()=>{ togglePaidFields(); renderStatusBadge(statusSelect.value); });
        togglePaidFields();
        renderStatusBadge(statusSelect.value);
    }
})();
</script>
@endpush