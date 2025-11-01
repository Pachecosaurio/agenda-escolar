@push('scripts')
<script>
(function(){
    function ready(fn){ if(document.readyState!=='loading'){fn();} else {document.addEventListener('DOMContentLoaded',fn);} }
    ready(() => {
        const form = document.getElementById('payment-form');
        if(!form) return;
        const statusSelect = form.querySelector('select[name="status"]');
        const amountInput = form.querySelector('input[name="amount"]');
        const paidDate = form.querySelector('input[name="paid_date"]');
        const dueDate = form.querySelector('input[name="due_date"]');
        const paidDeps = form.querySelectorAll('.paid-dependent');

        function togglePaid(){
            const isPaid = statusSelect && statusSelect.value === 'paid';
            paidDeps.forEach(el => el.classList.toggle('d-none', !isPaid));
            if(!isPaid){
                if(paidDate) paidDate.value = '';
                const pm = form.querySelector('select[name="payment_method"]');
                if(pm) pm.selectedIndex = 0;
                const ref = form.querySelector('input[name="reference"]');
                if(ref) ref.value='';
            }
        }
        if(statusSelect){ statusSelect.addEventListener('change', togglePaid); togglePaid(); }

        // Validación relación fechas (paid_date >= due_date)
        function validateDates(){
            if(paidDate && dueDate && paidDate.value && dueDate.value){
                if(paidDate.value < dueDate.value){
                    paidDate.setCustomValidity('La fecha de pago no puede ser anterior al vencimiento.');
                } else { paidDate.setCustomValidity(''); }
            }
        }
        if(paidDate){ paidDate.addEventListener('change', validateDates); }
        if(dueDate){ dueDate.addEventListener('change', validateDates); }

        // Formateo suave del monto al salir del input
        if(amountInput){
            amountInput.addEventListener('blur', () => {
                if(amountInput.value !== ''){
                    const n = parseFloat(amountInput.value);
                    if(!isNaN(n)) amountInput.value = n.toFixed(2);
                }
            });
        }

        // Animaciones fade-in si no se manejan ya globalmente
        document.querySelectorAll('.fade-in').forEach((el,i)=>{
            const d = el.getAttribute('data-delay') || (i*0.1);
            el.style.animationDelay = d+'s';
        });
    });
})();
</script>
@endpush