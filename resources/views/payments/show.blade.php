@extends('layouts.app')

@push('styles')
<style>
    body { background:#f4f6fb; }
    .page-wrapper-bg { background:linear-gradient(180deg,#f4f6fb 0%,#eef2f9 100%); min-height:100%; }

    .payment-show-hero { background: linear-gradient(135deg,#667eea 0%,#764ba2 50%,#a855f7 100%); position:relative; overflow:hidden; border-bottom:6px solid rgba(255,255,255,0.3); }
    .payment-show-hero .floating-circle { position:absolute; border-radius:50%; background:rgba(255,255,255,0.12); animation: floatShow 21s linear infinite; }
    .payment-show-hero .floating-circle:nth-child(1){ width:120px; height:120px; left:9%; animation-delay:0s; }
    .payment-show-hero .floating-circle:nth-child(2){ width:170px; height:170px; left:77%; animation-delay:5s; }
    .payment-show-hero .floating-circle:nth-child(3){ width:95px; height:95px; left:48%; animation-delay:10s; }
    @keyframes floatShow { 0%{ transform:translateY(90vh) rotate(0deg); opacity:0;} 10%{opacity:1;} 90%{opacity:1;} 100%{ transform:translateY(-120px) rotate(360deg); opacity:0;} }

    .show-glass-card { background:linear-gradient(135deg,rgba(255,255,255,0.16),rgba(255,255,255,0.06)); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.32); border-radius:28px; position:relative; overflow:hidden; }
    .show-glass-card:before { content:""; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#667eea,#764ba2,#a855f7,#667eea); background-size:300% 100%; animation:gradientShift 6s ease infinite; }
    @keyframes gradientShift {0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;}}

    .glass-mini-box { background:rgba(255,255,255,0.14); border:1px solid rgba(255,255,255,0.35); border-radius:14px; padding:1rem .95rem; }
    .glass-mini-box h6 { font-size:.7rem; letter-spacing:.5px; font-weight:600; text-transform:uppercase; }
    .notes-box { background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.35); border-radius:14px; padding:1rem 1rem; }
    .action-btn-gradient { background:linear-gradient(90deg,#667eea,#764ba2,#a855f7); border:none; color:#fff; font-weight:600; letter-spacing:.5px; box-shadow:0 8px 24px -6px rgba(103,65,148,.45); }
    .action-btn-gradient:hover { transform:translateY(-3px); box-shadow:0 14px 30px -4px rgba(103,65,148,.55); color:#fff; }
    .btn-outline-lite { background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.4); }
    .btn-outline-lite:hover { background:rgba(255,255,255,0.25); color:#fff; }
</style>
@endpush

@section('content')
<div class="page-wrapper-bg">
<div class="payment-show-hero py-5">
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="container hero-content py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="show-glass-card p-4 p-md-5 mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                        <div>
                            <h2 class="text-white mb-1">{{ $payment->title }}</h2>
                            <p class="text-white-50 mb-0">Detalle del pago</p>
                        </div>
                        <x-payment.status-badge :status="$payment->status" />
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Monto</h6>
                                <h4 class="text-white mb-0" style="font-size:1.4rem;">${{ number_format($payment->amount,2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Vence</h6>
                                <span class="text-white">{{ $payment->due_date?->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Categoría</h6>
                                <span class="text-white">{{ $payment->category_text }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Pagado el</h6>
                                <span class="text-white">{{ $payment->paid_date?->format('d/m/Y') ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Método</h6>
                                <span class="text-white">{{ $payment->payment_method ? ucfirst($payment->payment_method) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Referencia</h6>
                                <span class="text-white">{{ $payment->reference ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Creado</h6>
                                <span class="text-white">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="glass-mini-box h-100">
                                <h6 class="text-white-50 mb-1">Actualizado</h6>
                                <span class="text-white">{{ $payment->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="notes-box">
                                <h6 class="text-white-50 mb-2">Notas</h6>
                                <p class="text-white-50 mb-0">{{ $payment->notes ?: 'Sin notas adicionales.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('payments.edit',$payment) }}" class="btn action-btn-gradient"><i class="fas fa-edit me-1"></i> Editar</a>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-lite"><i class="fas fa-arrow-left me-1"></i> Volver</a>
                        <form action="{{ route('payments.destroy',$payment) }}" method="POST" onsubmit="return confirm('¿Eliminar pago?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-lite" style="border-color:rgba(220,53,69,0.55);background:rgba(220,53,69,0.25);"><i class="fas fa-trash me-1"></i>Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection