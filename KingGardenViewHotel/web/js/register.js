
addEventListener("DOMContentLoaded", (event) => {

	const first_name = document.getElementById("first_name");
	const last_name = document.getElementById("last_name");
	const user_name = document.getElementById("user_name");
	const email = document.getElementById("email");
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
	var [f1, l1, u1, e1, p1, p2] = [false, false, false, false, false, false];

	const address_1 = document.getElementById("address_1");
	const address_2 = document.getElementById("address_2");
	const address_3 = document.getElementById("address_3");
	const mobile = document.getElementById("mobile");
	const telephone = document.getElementById("telephone");
	var [t1, m1, a1, a2, a3] = [true, true, true, true, true];

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

	function validateUserName() {
		u1 = user_name.value != "" && !user_name.value.includes(" ") && user_name.value.length >= 4;
		user_name.classList.toggle("success-glow", u1);
		user_name.classList.toggle("fail-glow", !u1);
		sub_enable();
	};

	function validateEmail() {
		e1 = email.value.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
		email.classList.toggle("success-glow", e1);
		email.classList.toggle("fail-glow", !e1);
		sub_enable();
	};

	function validateFirstName() {
		f1 = first_name.value != "" && !first_name.value.includes(" ") && !/\d/.test(first_name.value) && !/[!@#$%^&*()\[\]{}\\|;:'",<.>/?`~]/.test(first_name.value);
		first_name.classList.toggle("success-glow", f1);
		first_name.classList.toggle("fail-glow", !f1);
		sub_enable();
	};

	function validateLastName() {
		l1 = last_name.value != "" && !last_name.value.includes(" ") && !/\d/.test(last_name.value) && !/[!@#$%^&*()\[\]{}\\|;:'",<.>/?`~]/.test(last_name.value);
		last_name.classList.toggle("success-glow", l1);
		last_name.classList.toggle("fail-glow", !l1);
		sub_enable();
	};

	function validateAddress1() {
		a1 = !/[!@#$%^&*\[\]{}|;:"<.>?`~]/.test(address_1.value) || address_1.value.trim() === "";
		address_1.classList.toggle("fail-glow", !a1);
		sub_enable();
	};

	function validateAddress2() {
		a2 = !/[!@#$%^&*\[\]{}|;:"<.>?`~]/.test(address_2.value) || address_2.value.trim() === "";
		address_2.classList.toggle("fail-glow", !a2);
		sub_enable();
	};

	function validateAddress3() {
		a3 = !/[!@#$%^&*\[\]{}|;:"<.>?`~]/.test(address_3.value) || address_3.value.trim() === "";
		address_3.classList.toggle("fail-glow", !a3);
		sub_enable();
	};

	function validateMobile() {
		m1 = /^(\+\d{1,2}\s?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/.test(mobile.value) || mobile.value.trim() === "";
		// 123-456-7890
		// (123) 456-7890
		// 123 456 7890
		// 123.456.7890
		// +91 (123) 456-7890
		// +94775434326
		mobile.classList.toggle("fail-glow", !m1);
		sub_enable();
	};
	
	function validateTelephone() {
		t1 = /^(\+\d{1,2}\s?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/.test(telephone.value) || telephone.value.trim() === "";
		telephone.classList.toggle("fail-glow", !t1);
		sub_enable();
	};

	function sub_enable() {
		(f1 && l1 && u1 && e1 && p1 && p2 && t1 && m1 && a1 && a2 && a3) ? submit_btn.disabled = false : submit_btn.disabled = true;
	};

	function resetGlow() {
		password.classList.remove("success-glow");
		//password.classList.remove("fail-glow");
		confirm_password.classList.remove("success-glow");
		//password2.classList.remove("fail-glow");
		user_name.classList.remove("success-glow");
		//userName.classList.remove("fail-glow");
		email.classList.remove("success-glow");
		//email.classList.remove("fail-glow");
		first_name.classList.remove("success-glow");
		//firstName.classList.remove("fail-glow");
		last_name.classList.remove("success-glow");
		//last_name.classList.remove("fail-glow");
	};

	password.addEventListener("focus", () => {
		validatePassword();
	});

	// password2.addEventListener("focus", () => {
	// 	validatePassword();
	// });
	// userName.addEventListener("focus", () => {
	// 	validateUserName();
	// });
	// email.addEventListener("focus", () => {
	// 	validateEmail();
	// });
	// firstName.addEventListener("focus", () => {
	// 	validateFirstName();
	// });
	// last_name.addEventListener("focus", () => {
	// 	validateLastName();
	// });
	// nic.addEventListener("focus", () => {
	// 	validateNic();
	// });
	// address_1.addEventListener("focus", () => {
	// 	validateAddress1();
	// });
	// address_2.addEventListener("focus", () => {
	// 	validateAddress2();
	// });
	// address_3.addEventListener("focus", () => {
	// 	validateAddress3();
	// });
	// mobile.addEventListener("focus", () => {
	// 	validateMobile();
	// });
	// telephone.addEventListener("focus", () => {
	// 	validateTelephone();
	// });

	password.addEventListener("input", () => {
		validatePassword();
	});
	confirm_password.addEventListener("input", () => {
		confirm_passwords();
	});
	user_name.addEventListener("input", () => {
		validateUserName();
	});
	email.addEventListener("input", () => {
		validateEmail();
	});
	first_name.addEventListener("input", () => {
		validateFirstName();
	});
	last_name.addEventListener("input", () => {
		validateLastName();
	});
	address_1.addEventListener("input", () => {
		validateAddress1();
	});
	address_2.addEventListener("input", () => {
		validateAddress2();
	});
	address_3.addEventListener("input", () => {
		validateAddress3();
	});
	mobile.addEventListener("input", () => {
		validateMobile();
	});
	telephone.addEventListener("input", () => {
		validateTelephone();
	});

	password.addEventListener("blur", () => {
		password_meter.classList.add("d-none");
		resetGlow();
	});
	confirm_password.addEventListener("blur", () => {
		password_meter.classList.add("d-none");
		resetGlow();
	});

	[user_name, email, first_name, last_name].forEach(item => {
		item.addEventListener("blur", event => {
			resetGlow();
		})
	  })

});
