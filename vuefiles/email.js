const p2s_email_form = {
    data() {
        return {
            form: {
                jform_id: '',
                jform_to: '',
                jform_subject: '',
                jform_emailtype: '',
                jform_body: '',
                jform_language: '*',
                jform_published: true
            },
            andClose: false,
            copytext: ''
        };
    },
    created() {

    },
    mounted() {

    },
    computed() {
    },
    async beforeMount() {

        const jform = document.getElementById('p2s_email');

        try {
            const form = JSON.parse(jform.innerText);
            this.form.jform_id = form.id;
            this.form.jform_to = form.to;
            this.form.jform_subject = form.subject;
            this.form.jform_emailtype = form.emailtype;
            this.form.jform_body = form.body;
            this.form.jform_language = form.language;
            this.form.jform_published = form.published;
            // jform.remove();
        } catch (err) {
        }



        const base_url = document.getElementById('base_url');
        try {
            this.base_url = base_url.innerText;
            base_url.remove();
        } catch (err) {
        }


        const currency = document.getElementById('currency');
        try {
            this.currency = currency.innerText;
            currency.remove();
        } catch (err) {
        }


        const locale = document.getElementById('locale');
        try {
            this.locale = locale.innerText;
            locale.remove();
        } catch (err) {
        }



    },
    methods: {

        toggle() {
            console.log(this.hasErroraccess);
            this.hasErroraccess = !this.hasErroraccess;
        },

        async saveItem() {
            this.form.jform_body = this.getFrameContents('jform_body');


            console.log(this.form);

            const params = {
                'itemid': this.form.jform_id,
                'to': this.form.jform_to,
                'subject': this.form.jform_subject,
                'emailtype': this.form.jform_emailtype,
                'body': this.form.jform_body,
                'language': this.form.jform_language,
                'published': (this.form.jform_published ? 1 : 0),

            };

            console.log(JSON.stringify(params));

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=email.save&format=raw", {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                redirect: 'follow',
                referrerPolicy: 'no-referrer',
                body: JSON.stringify(params)
            });


            const response = await request.json();

            if (response.success) {


                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });

                if (this.andClose) {
                    // if 'andClose' is true, redirect back to the list page
                    window.location.href = this.base_url + 'index.php?option=com_protostore&view=emailmanager';
                } else {
                    // if 'andClose' is still false, the user wants to stay on the page.
                    // this line makes sure that a new item gets the ID appended to the URL
                    const url = window.location.href;
                    if (url.indexOf('&id=') == -1) {
                        history.replaceState('', '', url + '&id=' + response.data.joomlaItem.id);
                    }

                    // we also need to make sure that the next save action doesn't trigger a create... we do this by adding the id to the form array
                    this.form.jform_id = response.data.id;

                }

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }


        },
        getFrameContents(elementId) {
            const iFrame = document.getElementById(elementId + '_ifr');
            let iFrameBody;
            if (iFrame.contentDocument) { // FF
                iFrameBody = iFrame.contentDocument.getElementById('tinymce');
            } else if (iFrame.contentWindow) { // IE
                iFrameBody = iFrame.contentWindow.document.getElementById('tinymce');
            }
            return iFrameBody.innerHTML;
        },
        serialize(obj) {
            var str = [];
            for (var p in obj)
                if (obj.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            return str.join("&");
        },
        copyText(text) {

            let copytext = document.querySelector('#copytext');
            copytext.setAttribute('type', 'text');
            copytext.value = text;
            copytext.select();

            try {
                document.execCommand('copy');
                UIkit.notification({
                    message: text + ' Copied',
                    status: 'primary',
                    pos: 'top-center',
                    timeout: 2500
                });
            } catch (err) {
                alert('Oops, unable to copy');
            }

            /* unselect the range */
            copytext.setAttribute('type', 'hidden');
            window.getSelection().removeAllRanges();
        }

    },
    components: {
        'p-inputswitch': primevue.inputswitch
    }
}

Vue.createApp(p2s_email_form).mount('#p2s_email_form')
