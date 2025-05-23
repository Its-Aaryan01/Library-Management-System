document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            const inputs = this.querySelectorAll("input[required]");
            let valid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    alert(`${input.placeholder} is required.`);
                    valid = false;
                }
            });
            if (!valid) e.preventDefault();
        });
    });
});
