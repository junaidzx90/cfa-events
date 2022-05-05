const cfa = new Vue({
    el: "#cfa_events",
    data: {
		isDisabled: true,
        currentPage: 1,
		max_pages: 0,
        previousEvents: [],
        currentFilter: ''
    },
    methods: {
        cfa_year_filter: function(event, year){

            cfa.currentPage = 1;
            cfa.currentFilter = year;

            jQuery.ajax({
				type: "get",
				url: cfa_ajax.ajaxurl,
				data: {
					action: "get_archive_events",
					nonce: cfa_ajax.nonce,
                    page: 1,
                    year: year
				},
                beforeSend: function(){
                    cfa.isDisabled = true;
                },
				dataType: "json",
				success: function (response) {
					cfa.isDisabled = false;
			
                    if (response.previousEvents) {
                        cfa.previousEvents = response.previousEvents;
                    }

                    if (response.maxpages) {
                        cfa.max_pages = response.maxpages
                    }

					jQuery(document).find('.years_btns .cfaActive').removeClass('cfaActive');
					jQuery(event.target).addClass('cfaActive');
				}
			});
        },
        loadmore_events: function(){

            jQuery.ajax({
				type: "get",
				url: cfa_ajax.ajaxurl,
				data: {
					action: "get_archive_events",
					nonce: cfa_ajax.nonce,
                    page: (cfa.currentPage+1),
                    year: cfa.currentFilter
				},
                beforeSend: function(){
                    cfa.isDisabled = true;
                },
				dataType: "json",
				success: function (response) {
					cfa.isDisabled = false;
			
                    if (response.previousEvents) {
                        response.previousEvents.forEach(element => {
                            cfa.previousEvents.push(element);
                        });
                        cfa.currentPage += 1;
                    }

                    if (response.maxpages) {
                        cfa.max_pages = response.maxpages
                    }
				}
			});
        }
    },
    updated: function(){

    },
    mounted: function () {

		let cfaEvents = new Promise((resolve, reject) => {
			jQuery.ajax({
				type: "get",
				url: cfa_ajax.ajaxurl,
				data: {
					action: "get_archive_events",
					nonce: cfa_ajax.nonce
				},
				dataType: "json",
				success: function (response) {
					resolve(response);
				}
			});
		});

		cfaEvents.then(response => {
			cfa.isDisabled = false;
            if (response.previousEvents) {
                cfa.previousEvents = response.previousEvents;
            }

            if (response.maxpages) {
                cfa.max_pages = response.maxpages;
            }
		})
	}
});

// Single page
const cfasingle = new Vue({
    el: "#event_page",
    data: {
        isDisabled: false,
        isForm: true,
        registrant_name: "",
        registrant_email: "",
        registrant_phone: "",
        registrant_company: "",
        participants: "",
        submittedAlert: ""
    },
    methods: {
        register_form_submit: function(e){
            e.preventDefault();
            let event_id = document.querySelector("input[name='event_id']").value;
            let registrant_name = this.registrant_name;
            let registrant_email = this.registrant_email;
            let registrant_phone = this.registrant_phone;
            let registrant_company = this.registrant_company;
            let participants = this.participants;

            let data = {event_id, registrant_name, registrant_email, registrant_phone, registrant_company, participants};

            jQuery.ajax({
                type: "post",
                url: cfa_ajax.ajaxurl,
                data: {
                    action: "registrants_register",
                    nonce: cfa_ajax.nonce,
                    data: data
                },
                beforeSend: function(){
                    cfasingle.isDisabled = true;
                },
                dataType: "json",
                success: function (response) {
                    cfasingle.isDisabled = false;
                    if(response.success){
                        cfasingle.isForm = false;
                        let alerts = `<h3 style="margin-bottom: 10px;" class="head3"><strong>Thank</strong> You</h3>${response.success}`;

                        cfasingle.submittedAlert = alerts;
                    }
                    if(response.error){
                        alert(response.error);
                    }
                }
            });
        }
    }
});