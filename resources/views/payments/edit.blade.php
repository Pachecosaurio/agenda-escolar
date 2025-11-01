@extends('layouts.app')

@push('styles')
<style>
    /* Estilos específicos vista edición pagos (coherente con index) */
    .payment-edit-hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #a855f7 100%); position:relative; overflow:hidden; }
    .payment-edit-hero .floating-circle { position:absolute; border-radius:50%; background:rgba(255,255,255,0.12); animation: floatEdit 22s linear infinite; }
    .payment-edit-hero .floating-circle:nth-child(1){ width:120px; height:120px; left:10%; animation-delay:0s; }
    .payment-edit-hero .floating-circle:nth-child(2){ width:180px; height:180px; left:75%; animation-delay:5s; }
    .payment-edit-hero .floating-circle:nth-child(3){ width:90px; height:90px; left:50%; animation-delay:10s; }
    @keyframes floatEdit { 0%{ transform:translateY(90vh) rotate(0deg); opacity:0;} 10%{opacity:1;} 90%{opacity:1;} 100%{ transform:translateY(-120px) rotate(360deg); opacity:0;} }

    .edit-glass-card { background:linear-gradient(135deg,rgba(255,255,255,0.15),rgba(255,255,255,0.05)); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.3); border-radius:26px; position:relative; overflow:hidden; }
    .edit-glass-card:before { content:""; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#667eea,#764ba2,#a855f7,#667eea); background-size:300% 100%; animation:gradientShift 6s ease infinite; }
    @keyframes gradientShift {0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;}}
    .edit-section-title { font-size:1.05rem; letter-spacing:.5px; font-weight:600; color:#4f46e5; text-transform:uppercase; }
    .fieldset-box { background:rgba(255,255,255,0.14); border:1px solid rgba(255,255,255,0.35); border-radius:18px; padding:1.25rem 1.25rem .75rem; position:relative; }
    .fieldset-box legend { font-size:.85rem; letter-spacing:.5px; font-weight:600; padding:0 .65rem; background:rgba(255,255,255,0.25); border-radius:12px; backdrop-filter:blur(4px); }
    .form-label { font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; }
    .form-control, .form-select { border-radius:14px; background:rgba(255,255,255,0.9); border:1px solid #d1d5db; }
    .form-control:focus, .form-select:focus { border-color:#764ba2; box-shadow:0 0 0 .15rem rgba(118,75,162,0.25); }
    .paid-dependent { transition:.35s ease; }
    .status-preview-badge { padding:.45rem .85rem; border-radius:30px; font-size:.75rem; font-weight:600; letter-spacing:.5px; display:inline-flex; align-items:center; gap:.35rem; }
    .status-preview-badge i { font-size:.85rem; }
    .status-badge-pending { background:#fff7ed; border:1px solid #fdba74; color:#c2410c; }
    .status-badge-paid { background:#ecfdf5; border:1px solid #6ee7b7; color:#047857; }
    .status-badge-overdue { background:#fef2f2; border:1px solid #fca5a5; color:#b91c1c; }

    .action-btn-gradient { background:linear-gradient(90deg,#667eea,#764ba2,#a855f7); border:none; color:#fff; font-weight:600; letter-spacing:.5px; box-shadow:0 8px 24px -6px rgba(103,65,148,.45); }
    .action-btn-gradient:hover { transform:translateY(-3px); box-shadow:0 14px 30px -4px rgba(103,65,148,.55); color:#fff; }
    .action-btn-gradient:active { transform:translateY(-1px) scale(.97); }
    .btn-outline-lite { background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.4); }
    .btn-outline-lite:hover { background:rgba(255,255,255,0.25); color:#fff; }

    .section-divider { height:1px; background:linear-gradient(90deg,rgba(255,255,255,0),rgba(118,75,162,.6),rgba(255,255,255,0)); margin:2rem 0 1.5rem; }
    .helper-text { font-size:.7rem; letter-spacing:.5px; color:#6b7280; text-transform:uppercase; }

    body { background:#f4f6fb; }
    .page-wrapper-bg { background:linear-gradient(180deg,#f4f6fb 0%,#eef2f9 100%); min-height:100%; }
    .payment-edit-hero { border-bottom:6px solid rgba(255,255,255,0.3); }

    @media (max-width: 768px){
        .edit-glass-card { border-radius:22px; }
        .form-control, .form-select { font-size:.9rem; }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper-bg">
<div class="payment-edit-hero py-5 mb-0">
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9 text-center mb-4">
                <div class="mx-auto mb-4" style="width:120px;height:120px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.18);border:3px solid rgba(255,255,255,0.4);backdrop-filter:blur(10px);">
                    <i class="fas fa-edit text-white" style="font-size:3rem;"></i>
                </div>
                <h1 class="text-white fw-bold mb-2" style="letter-spacing:-1px;">Editar Pago</h1>
                <p class="text-white-50 mb-0">Actualiza la información y estado del pago seleccionado</p>
            </div>
            <div class="col-lg-9">
                <div class="edit-glass-card p-4 p-md-5">
                    <form action="{{ route('payments.update',$payment) }}" method="POST" id="payment-form">
                        @csrf
                        @method('PUT')
                        <fieldset class="fieldset-box mb-4">
                            <legend>Datos Básicos</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Título *</label>
                                    <input type="text" name="title" value="{{ old('title',$payment->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                                    @error('title')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Monto *</label>
                                    <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount',$payment->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
                                    @error('amount')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Categoría *</label>
                                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                        @foreach($categories as $key => $label)
                                            <option value="{{ $key }}" @selected(old('category',$payment->category)===$key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Vencimiento *</label>
                                    <input type="date" name="due_date" value="{{ old('due_date',$payment->due_date?->format('Y-m-d')) }}" class="form-control @error('due_date') is-invalid @enderror" required>
                                    @error('due_date')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-flex justify-content-between align-items-center">Estado * <span class="helper-text">Vista previa</span></label>
                                    <select name="status" class="form-select status-select @error('status') is-invalid @enderror" required id="status-select">
                                        <option value="pending" @selected(old('status',$payment->status)==='pending')>Pendiente</option>
                                        <option value="paid" @selected(old('status',$payment->status)==='paid')>Pagado</option>
                                        <option value="overdue" @selected(old('status',$payment->status)==='overdue')>Vencido</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                    <div class="mt-2" id="status-preview"></div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="fieldset-box mb-4">
                            <legend>Información de Pago</legend>
                            <div class="row g-3">
                                <div class="col-md-3 paid-dependent @if(old('status',$payment->status)!=='paid') d-none @endif">
                                    <label class="form-label">Fecha de Pago</label>
                                    <input type="date" name="paid_date" value="{{ old('paid_date',$payment->paid_date?->format('Y-m-d')) }}" class="form-control @error('paid_date') is-invalid @enderror">
                                    @error('paid_date')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 paid-dependent @if(old('status',$payment->status)!=='paid') d-none @endif">
                                    <label class="form-label">Método</label>
                                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                                        <option value="" @selected(old('payment_method',$payment->payment_method)==='')>Seleccione</option>
                                        <option value="cash" @selected(old('payment_method',$payment->payment_method)==='cash')>Efectivo</option>
                                        <option value="card" @selected(old('payment_method',$payment->payment_method)==='card')>Tarjeta</option>
                                        <option value="transfer" @selected(old('payment_method',$payment->payment_method)==='transfer')>Transferencia</option>
                                        <option value="online" @selected(old('payment_method',$payment->payment_method)==='online')>En Línea</option>
                                    </select>
                                    @error('payment_method')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 paid-dependent @if(old('status',$payment->status)!=='paid') d-none @endif">
                                    <label class="form-label">Referencia</label>
                                    <input type="text" name="reference" value="{{ old('reference',$payment->reference) }}" class="form-control @error('reference') is-invalid @enderror" placeholder="N° transacción / comprobante">
                                    @error('reference')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Notas</label>
                                    <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Detalles adicionales">{{ old('notes',$payment->notes) }}</textarea>
                                    @error('notes')<div class="invalid-feedback d-block small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn action-btn-gradient"><i class="fas fa-save me-1"></i> Guardar Cambios</button>
                            <a href="{{ route('payments.show',$payment) }}" class="btn btn-outline-lite"><i class="fas fa-eye me-1"></i> Ver</a>
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
        if (statusSelect.value === 'paid') {
            paidDependent.forEach(el => el.classList.remove('d-none'));
        } else {
            paidDependent.forEach(el => el.classList.add('d-none'));
        }
    }

    statusSelect.addEventListener('change', ()=>{ togglePaidFields(); renderStatusBadge(statusSelect.value); });
    togglePaidFields();
    renderStatusBadge(statusSelect.value);
})();
</script>
@endpush