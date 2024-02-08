const { createApp, ref } = Vue

const app = createApp({
    data() {
        return {
            COMPANY_NAME  : 'Ustaz PRO',
            CLIENT_ID     : '928580067145-b9ub9jr25h1k9dejl47gak2nvjjqsu4c.apps.googleusercontent.com',
            API_KEY       : 'AIzaSyDxk9JJ5BL_7gtEGJFNPRIGLo3qEOTsApk',
            SPREADSHEET_ID: '1Di6ReUjyV6OO4oaLZKTomfKgguvqn8SATu6lV0huZxM',
            SHEET_ID      : 'Consolidated',
            SCOPES        : 'https : //www.googleapis.com/auth/spreadsheets.readonly',

            tokenClient   : null,
            gapiInited    : false,
            gisInited     : false,
            
            locale        : 'kk',
            locales       : [ {code: 'kk', name: 'Қаз'}, {code: 'ru', name: 'Рус'} ],
            certificate   : '',
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
                }
            },
        }
    },
    methods: {
        gapiLoaded() {
            gapi.load( 'client', this.initializeGapiClient );
        },
        async initializeGapiClient() {
            await gapi.client.init({
                apiKey: this.API_KEY,
            });
            this.gapiInited = true;
        },
        gisLoaded() {
            this.tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: this.CLIENT_ID,
                scope    : this.SCOPES,
                callback : '', //defined later
            });
            this.gisInited = true;
        },
        t( key ) {
            return this.messages[ key ][ this.locale ]
        },
        changeLocale( locale ) {
            this.locale = ( locale == 'ru' ) ? 'ru' : 'kk'
        }
    },
    created() {
        document.title = this.COMPANY_NAME + ' | ' + this.t( 'certificate_check' )
        this.gisLoaded()
        this.gapiLoaded()
    },
})

app.mount('#vapp')