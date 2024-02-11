const mes = {
    data() {
        return {
            // Translation variables
            locale    : 'kk', // by default language is set here
            locales   : [ {code: 'kk', name: 'Қаз'}, {code: 'ru', name: 'Рус'} ],
            messages  : {
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
                entry_not_found: {
                    kk: 'Осындай номермен сертификат табылмады',
                    ru: 'Не найден сертификат с таким номером'
                },
                entry_found: {
                    kk: 'Келесі сертификат табылды',
                    ru: 'Найден следующий сертификат'
                },
                full_name: {
                    kk: 'Аты жөні',
                    ru: 'Фамилия Имя'
                },
                course_name: {
                    kk: 'Курстың бағдарламалық атауы',
                    ru: 'Наименование программы курса'
                },
                spent_hours: {
                    kk: 'Сағат саны',
                    ru: 'Количество часов'
                },
                start_date: {
                    kk: 'Оқудың басталған күні',
                    ru: 'Дата начала обучения'
                },
                end_date: {
                    kk: 'Оқудың аяқталған күні',
                    ru: 'Дата завершения обучения'
                },
                certificate_number: {
                    kk: 'Сертификат номеры',
                    ru: 'Номер сертификата'
                },
            },
        }
    },
    methods: {
        t( key ) {
            if( Object.hasOwn( this.messages, key) )
                return this.messages[ key ][ this.locale ]
            else return key
        },
        changeLocale( locale ) {
            this.locale = ( locale === 'ru' ) ? 'ru' : 'kk'
            localStorage.setItem( 'certificate_check_locale', this.locale )
            this.setTitle()
        },
        restoreLocale() {
            this.changeLocale( localStorage.getItem( 'certificate_check_locale' ) )
        },
    },
    created() {
        this.restoreLocale()
    },
}