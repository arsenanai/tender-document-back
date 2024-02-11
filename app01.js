const { createApp, ref } = Vue

createApp({
    mixins: [gsh,mes],
    data() {
        return {
            // Application constants
            COMPANY_NAME: 'Ustaz PRO',
            certificate : '',
            state       : 'submitting',
            validation  : 'validation_rules',
        }
    },
    computed: {
        formIsValid() {
            return this.certificate.length > 9
        },
    },
    methods: {
        setTitle() {
            document.title = this.COMPANY_NAME + ' | ' + this.t( 'certificates_check' )
        },
        async submitCheck() {
            console.log( 'form submit is triggered' );
            this.response   = null;
            this.state      = 'loading'
            this.answers    = []
            this.controller = new AbortController()
            if ( this.formIsValid ) {
                console.log( 'validation passed' );
                this.searchData()
            }
        }
    },
    created() {
        this.setTitle()
    }
}).mount('#vapp')