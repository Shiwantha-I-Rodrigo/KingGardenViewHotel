addEventListener("DOMContentLoaded", (event) => {

	const email = document.getElementById("email");
    const confirm_mail = document.getElementById("confirm_email");
	const password = document.getElementById("password");
	const confirm_password = document.getElementById("confirm_password");
	const password_meter = document.getElementById("password_meter");
	const length_tick = document.getElementById("length_tick");
	const length_cross = document.getElementById("length_cross");
	const upper_tick = document.getElementById("upper_tick");
	const upper_cross = document.getElementById("upper_cross");
	const lower_tick = document.getElementById("lower_tick");
	const lower_cross = document.getElementById("lower_cross");
	const number_tick = document.getElementById("number_tick");
	const number_cross = document.getElementById("number_cross");
	const char_tick = document.getElementById("char_tick");
	const char_cross = document.getElementById("char_cross");
	const submit_btn = document.getElementById("submit_btn");
    const send_btn = document.getElementById("send_btn");
	var [e1, e2, p1, p2] = [false, false, false, false];

	function validatePassword() {
		const value = password.value;
		const hasLength = value.length >= 8;
		const hasUpperCase = /[A-Z]/.test(value);
		const hasLowerCase = /[a-z]/.test(value);
		const hasNumber = /\d/.test(value);
		const hasChar = /[!@#$%^&*()\[\]{}\\|;:'",<.>/?`~]/.test(value);

		if (hasLength) {
			length_tick.classList.remove("d-none");
			length_cross.classList.add("d-none");
		} else {
			length_tick.classList.add("d-none");
			length_cross.classList.remove("d-none");
		}

		if (hasUpperCase) {
			upper_tick.classList.remove("d-none");
			upper_cross.classList.add("d-none");
		} else {
			upper_tick.classList.add("d-none");
			upper_cross.classList.remove("d-none");
		}

		if (hasLowerCase) {
			lower_tick.classList.remove("d-none");
			lower_cross.classList.add("d-none");
		} else {
			lower_tick.classList.add("d-none");
			lower_cross.classList.remove("d-none");
		}

		if (hasNumber) {
			number_tick.classList.remove("d-none");
			number_cross.classList.add("d-none");
		} else {
			number_tick.classList.add("d-none");
			number_cross.classList.remove("d-none");
		}

		if (hasChar) {
			char_tick.classList.remove("d-none");
			char_cross.classList.add("d-none");
		} else {
			char_tick.classList.add("d-none");
			char_cross.classList.remove("d-none");
		}

		p1 = hasLength && hasUpperCase && hasLowerCase && hasNumber && hasChar;

		password_meter.classList.remove("d-none");

		password.classList.toggle("success-glow", p1);
		password.classList.toggle("fail-glow", !p1);
		sub_enable();
	};

	function confirm_passwords() {
		p2 = password.value == confirm_password.value;
		confirm_password.classList.toggle("success-glow", p1 && p2);
		confirm_password.classList.toggle("fail-glow", !p1 || !p2);
		sub_enable();
	};

	function validate_email() {
		e1 = email.value.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
		email.classList.toggle("success-glow", e1);
		email.classList.toggle("fail-glow", !e1);
		sub_enable();
	};

    function confirm_email() {
		e2 = email.value == confirm_mail.value;
		confirm_mail.classList.toggle("success-glow", e1 && e2);
		confirm_mail.classList.toggle("fail-glow", !e1 || !e2);
		sub_enable();
	};

	function sub_enable() {
		(p1 && p2) ? submit_btn.disabled = false : submit_btn.disabled = true;
        (e1 && e2) ? send_btn.disabled = false : send_btn.disabled = true;
	};

	function resetGlow() {
		password.classList.remove("success-glow");
		confirm_password.classList.remove("success-glow");
		email.classList.remove("success-glow");
        confirm_mail.classList.remove("success-glow");
	};

	password.addEventListener("focus", () => {
		validatePassword();
	});

	password.addEventListener("input", () => {
		validatePassword();
	});
	confirm_password.addEventListener("input", () => {
		confirm_passwords();
	});
	email.addEventListener("input", () => {
		validate_email();
	});
    confirm_mail.addEventListener("input", () => {
		confirm_email();
	});

	password.addEventListener("blur", () => {
		password_meter.classList.add("d-none");
		resetGlow();
	});
	confirm_password.addEventListener("blur", () => {
		password_meter.classList.add("d-none");
		resetGlow();
	});

	[email, confirm_mail].forEach(item => {
		item.addEventListener("blur", event => {
			resetGlow();
		})
	})

});
