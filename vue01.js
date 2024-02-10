const { createApp, ref } = Vue

createApp({
    mixins: [gsh],
    data() {
        return {
            // Application constants
            COMPANY_NAME  : 'Ustaz PRO',
            // Google API constants
            CLIENT_ID     : '928580067145-b9ub9jr25h1k9dejl47gak2nvjjqsu4c.apps.googleusercontent.com',
            API_KEY       : 'AIzaSyDxk9JJ5BL_7gtEGJFNPRIGLo3qEOTsApk',
            SPREADSHEET_ID: '1Di6ReUjyV6OO4oaLZKTomfKgguvqn8SATu6lV0huZxM',
            SHEET_ID      : 'Consolidated',
            SCOPES        : 'https://www.googleapis.com/auth/spreadsheets.readonly',
            // Google API variables
            tokenClient   : null,
            gapiInited    : false,
            gisInited     : false,
            // Translation variables
            locale        : 'kk', // by default language is set here
            locales       : [ {code: 'kk', name: 'Қаз'}, {code: 'ru', name: 'Рус'} ],
            messages      : {
                certificates_check: {
                    kk: 'Сертификаттарды тексеру',
                    ru: 'Проверка сертификатов'
                },
                contact_text: {
                    kk: 'Сізге де осындай тексеру парақшасы керек пе?',
                    ru: 'Вам тоже нужна такая страница проверки?'
                },
                check_label: {
                    kk: 'Тексеру',
                    ru: 'Проверка'
                },
                validation_rules: {
                    kk: 'Сертификат номерының ұзындығы 10нан кем емес болуы шарт',
                    ru: 'Номер сертификата должен быть длиной не менее 10 символов'
                },
                contact_support: {
                    kk: 'Бірнәрсе дұрыс болмады, техникалық қолдауға хабарласыңыз',
                    ru: 'Свяжитесь с технической поддержкой, что-то пошло не так'
                }
            },

            certificate   : '',
            state         : 'submitting',
            validation    : 'validation_rules'
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
        t( key ) {
            if( Object.hasOwn( this.messages, key) )
                return this.messages[ key ][ this.locale ]
            else return ''
        },
        changeLocale( locale ) {
            this.locale = ( locale === 'ru' ) ? 'ru' : 'kk'
            localStorage.setItem( 'certificate_check_locale', this.locale )
            this.setTitle()
        },
        restoreLocale( ) {
            this.changeLocale( localStorage.getItem( 'certificate_check_locale' ) )
        },
        submitCheck() {
            console.log( 'form submit is triggered' );
            this.state = 'loading'
            if ( this.formIsValid && this.isInteractionAllowed ) {
                console.log( 'validation passed' );
            }
        }
    },
    created() {
        this.setTitle()
        this.restoreLocale()
    },
}).mount('#vapp')