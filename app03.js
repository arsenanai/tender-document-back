const { createApp, ref } = Vue

createApp({
    mixins: [gsh,mes],
    data() {
        return {
            // Application constants
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
            document.title = this.t( 'certificates_check' )
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
        },
        getButtonLabel() {
            if( this.state === 'loading' && !this.notFoundEverywhere()) {
                return this.t( 'loading' )
            } else {
                return this.t( 'check_label' )
            }
        }
    },
    created() {
        this.setTitle()
    }
}).mount('#vapp')