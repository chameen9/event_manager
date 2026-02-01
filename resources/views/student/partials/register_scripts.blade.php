<!--**********************************
    Scripts
***********************************-->

<!-- Required vendors -->
<script src="vendor/global/global.min.js"></script>
<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

<script src="vendor/jquery-steps/build/jquery.steps.min.js"></script>
<script src="vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js"></script>

<!-- Svganimation scripts -->
<script src="vendor/svganimation/vivus.min.js"></script>
<script src="vendor/svganimation/svg.animation.js"></script>

<script src="js/custom.min.js"></script>
<script src="js/dlabnav-init.js"></script>

<script>
    $(document).ready(function(){
        // SmartWizard initialize
        $('#smartwizard').smartWizard(); 
    });
</script>

<script>
    window.hasIncompletePrograms = @json($hasIncompletePrograms);
</script>

<script>
    (function ($) {

        $(document).ready(function () {

            const wizard = $('#smartwizard');
            if (!wizard.length) return;

            // --------------------------------------------------
            // Check if current step has empty required fields
            // --------------------------------------------------
            function hasEmptyRequiredFields(stepIndex) {
                const stepPane = wizard.find('.tab-pane').eq(stepIndex);
                let hasEmpty = false;

                stepPane.find('input, select, textarea').each(function () {
                    const field = $(this);

                    if (!field.prop('required')) return;
                    if (field.prop('disabled')) return;

                    if (!field.val() || field.val().toString().trim() === '') {
                        hasEmpty = true;
                        return false;
                    }
                });

                return hasEmpty;
            }

            // --------------------------------------------------
            // Enable / Disable NEXT button (visual + functional)
            // --------------------------------------------------
            function updateNextButton(stepIndex) {
                const nextBtn = wizard.find('.sw-btn-next');
                if (!nextBtn.length) return;

                let disableNext = false;

                // Rule 1: required fields
                if (hasEmptyRequiredFields(stepIndex)) {
                    disableNext = true;
                }

                // Rule 2: declaration checkbox (STEP 2 only)
                if (
                    window.hasIncompletePrograms === true &&
                    stepIndex === 1
                ) {
                    const checkbox = $('#completion_acknowledgement');
                    if (checkbox.length && !checkbox.is(':checked')) {
                        disableNext = true;
                    }
                }

                if (disableNext) {
                    nextBtn
                        .addClass('disabled')
                        .prop('disabled', true)
                        .css({
                            'pointer-events': 'none',
                            'opacity': '0.6',
                            'cursor': 'not-allowed'
                        });
                } else {
                    nextBtn
                        .removeClass('disabled')
                        .prop('disabled', false)
                        .css({
                            'pointer-events': '',
                            'opacity': '',
                            'cursor': ''
                        });
                }
            }

            // --------------------------------------------------
            // BLOCK: STEP 2 -> STEP 3 if checkbox not checked
            // --------------------------------------------------
            wizard.on('leaveStep', function (e, anchor, currentStepIndex, nextStepIndex, stepDirection) {

                if (window.hasIncompletePrograms !== true) {
                    return true;
                }

                // STEP 2 index = 1
                if (currentStepIndex === 1 && stepDirection === 'forward') {
                    const checkbox = $('#completion_acknowledgement');
                    if (checkbox.length && !checkbox.is(':checked')) {
                        return false;
                    }
                }

                return true;
            });

            // --------------------------------------------------
            // When step is shown
            // --------------------------------------------------
            wizard.on('showStep', function (e, anchor, stepIndex) {

                // Force-disable NEXT immediately on STEP 2 load
                if (stepIndex === 1 && window.hasIncompletePrograms === true) {
                    const nextBtn = wizard.find('.sw-btn-next');
                    nextBtn
                        .addClass('disabled')
                        .prop('disabled', true)
                        .css({
                            'pointer-events': 'none',
                            'opacity': '0.6',
                            'cursor': 'not-allowed'
                        });
                }

                updateNextButton(stepIndex);
            });

            // --------------------------------------------------
            // Re-check when inputs change
            // --------------------------------------------------
            wizard.on('input change', 'input, select, textarea', function () {
                const stepIndex = wizard.smartWizard('getStepIndex');
                updateNextButton(stepIndex);
            });

            // --------------------------------------------------
            // Checkbox change
            // --------------------------------------------------
            $(document).on('change', '#completion_acknowledgement', function () {
                const stepIndex = wizard.smartWizard('getStepIndex');
                updateNextButton(stepIndex);
            });

        });

    })(jQuery);
</script>

<script>
    window.PRICING = {
        photos: {
            burst: {{ photo_price('12x8" Burst Photo') }},
            full: {{ photo_price('12x8" Full Photo') }},
            family: {{ photo_price('12x8" Family Photo') }}
        },
        additionalSeat: {{ additional_seat_price('Additional') }},
        shuttleSeat: {{ shuttle_seat_price('Shuttle') }},
        packagePrice: {{ $packagePrice ?? 6500 }}
    };
</script>

<script>
    (function () {

        // Prices from backend (DB via helpers)
        const PHOTO_PRICES   = window.PRICING.photos;
        const SEAT_PRICE     = window.PRICING.additionalSeat;
        const SHUTTLE_PRICE  = window.PRICING.shuttleSeat;
        const PACKAGE_PRICE  = window.PRICING.packagePrice;

        // Inputs
        const burstInput   = document.getElementById('burst_photo_count');
        const fullInput    = document.getElementById('full_photo_count');
        const familyInput  = document.getElementById('family_photo_count');
        const seatInput    = document.getElementById('additional_seat_count');
        const shuttleInput = document.getElementById('shuttle_seat_count');

        // Outputs
        const photoTotalEl   = document.getElementById('photo_total');
        const seatTotalEl    = document.getElementById('additional_seat_total');
        const shuttleTotalEl = document.getElementById('shuttle_seat_total');
        const finalTotalEl   = document.getElementById('final_amount');
        const finalTotalE2   = document.getElementById('final_amount_2');
        const finalInputEl   = document.getElementById('final_amount_input');

        if (!finalTotalEl) return;

        function getInt(el) {
            return el ? parseInt(el.value, 10) || 0 : 0;
        }

        function formatLKR(amount) {
            return 'LKR. ' + amount.toLocaleString('en-LK', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function calculateTotals() {

            // Photo totals (different prices per type)
            const burstTotal  = getInt(burstInput)  * PHOTO_PRICES.burst;
            const fullTotal   = getInt(fullInput)   * PHOTO_PRICES.full;
            const familyTotal = getInt(familyInput) * PHOTO_PRICES.family;

            const photoTotal = burstTotal + fullTotal + familyTotal;

            // Additional seats
            const seatTotal = getInt(seatInput) * SEAT_PRICE;

            // Shuttle
            const shuttleTotal = getInt(shuttleInput) * SHUTTLE_PRICE;

            // Update individual totals
            if (photoTotalEl)   photoTotalEl.textContent   = formatLKR(photoTotal);
            if (seatTotalEl)    seatTotalEl.textContent    = formatLKR(seatTotal);
            if (shuttleTotalEl) shuttleTotalEl.textContent = formatLKR(shuttleTotal);

            // Final total
            const finalAmount =
                PACKAGE_PRICE +
                photoTotal +
                seatTotal +
                shuttleTotal;

            finalTotalEl.textContent = formatLKR(finalAmount);

            if (finalTotalE2) {
                finalTotalE2.textContent = formatLKR(finalAmount);
            }

            if (finalInputEl) {
                finalInputEl.value = finalAmount;
            }
        }

        // Listen to changes
        [
            burstInput,
            fullInput,
            familyInput,
            seatInput,
            shuttleInput
        ].forEach(input => {
            if (input) {
                input.addEventListener('input', calculateTotals);
            }
        });

        // Initial run
        calculateTotals();

    })();
</script>