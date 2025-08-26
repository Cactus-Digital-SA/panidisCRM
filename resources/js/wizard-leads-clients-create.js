(function () {
    const wizardModernVertical = document.querySelector('.wizard-modern-vertical');

    if (typeof wizardModernVertical !== undefined && wizardModernVertical !== null) {
    const wizardModernVerticalBtnNextList = [].slice.call(wizardModernVertical.querySelectorAll('.btn-next')),
        wizardModernVerticalBtnPrevList = [].slice.call(wizardModernVertical.querySelectorAll('.btn-prev'));
        // wizardModernVerticalBtnSubmit = wizardModernVertical.querySelector('.btn-submit');

    const modernVerticalStepper = new Stepper(wizardModernVertical, {
        linear: false
    });
    if (wizardModernVerticalBtnNextList) {
        wizardModernVerticalBtnNextList.forEach(wizardModernVerticalBtnNext => {
        wizardModernVerticalBtnNext.addEventListener('click', event => {
            modernVerticalStepper.next();
            event.preventDefault();
        });
        });
    }
    if (wizardModernVerticalBtnPrevList) {
        wizardModernVerticalBtnPrevList.forEach(wizardModernVerticalBtnPrev => {
        wizardModernVerticalBtnPrev.addEventListener('click', event => {
            modernVerticalStepper.previous();
            event.preventDefault();
        });
        });
    }
    }
})();
