<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Заявка для получения демо-доступа в КонтурФокус</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <style>
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        td {
            padding: 3px;
            text-align: right;
        }

        html {
            font-family: Arial, serif, serif;
            font-size: 16px;
        }

        input {
            font-size: 16px;
            border-width: 2px;
            padding: 8px 10px;
            border-radius: 10px;
            transition: all linear 0.2s;
            margin: 1px;
        }

        input:focus {
            outline: none !important;
            border-radius: 15px;
        }

        .error {
            margin-top: 1px;
            font-size: 12px;
            color: red;
            visibility: hidden;
            margin-bottom: 5px;
        }

        .input-error {
            border-color: red
        }

        button {
            font-size: 18px;
            padding: 5px 10px;
            border-radius: 10px;
            border-width: 1px;
            margin-top: 5px;
            transition: all linear 0.2s;
        }

        button:hover {
            border-radius: 15px;
        }

        #sent {
            margin: 3px;
            color: limegreen;
            visibility: hidden;
        }
    </style>
    <script defer>
        var sendClicked = false

        function isValidEmail(email) {
            return email.match(/^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)])$/)
        }

        function lengthValidator(len) {
            return value => value.length > len
        }

        validators = {
            'name-input': {
                'validator': lengthValidator(0),
                'message': 'Введите имя'
            },
            'organisation-input': {
                'validator': lengthValidator(0),
                'message': 'Введите название вашей организации'
            },
            'position-input': {
                'validator': lengthValidator(0),
                'message': 'Введите название вашей должности'
            },
            'phone-input': {
                'validator': lengthValidator(0),
                'message': 'Введите правильный номер телефона'
            },
            'email-input': {
                'validator': isValidEmail,
                'message': 'Введите правильный e-mail'
            }
        }

        function invalidate(input) {
            input.classList.add('input-error')
            document.getElementById(input.id + '-error').style.visibility = 'visible'
        }

        function makeValid(input) {
            input.classList.remove('input-error')
            document.getElementById(input.id + '-error').style.visibility = 'hidden'
        }

        const inputs = document.getElementsByTagName('input')
        for (const input of inputs) {
            input.addEventListener('input', e => {
                if (!sendClicked) {
                    return
                }

                if (validators[input.id]['validator'](e.target.value)) {
                    makeValid(input)
                } else {
                    invalidate(input)
                }
            })
        }

        function runChecks() {
            for (let input of document.getElementsByTagName('input')) {
                if (validators[input.id]['validator'](input.value)) {
                    makeValid(input)
                } else {
                    invalidate(input)
                }
            }
        }

        function isAllValid() {
            for (let inp of document.getElementsByTagName('input')) {
                if (!validators[inp.id]['validator'](inp.value)) {
                    return false
                }
            }
            return true
        }

        function getFields() {
            const inputs = document.getElementsByTagName('input')
            const result = {}
            for (let element of inputs) {
                result[element.id] = element.value
            }
            return result
        }

        async function onClick() {
            sendClicked = true
            runChecks()
            if (!isAllValid()) {
                return
            }
            let fields = getFields()
            let body = {
                'fullName': fields['name-input'],
                'organisation': fields['organisation-input'],
                'position': fields['position-input'],
                'phoneNumber': fields['phone-input'],
                'email': fields['email-input']
            }
            document.getElementById('sent').style.visibility = 'hidden'
            const response = await fetch('/submit-form', {
                method: 'POST',
                body: JSON.stringify(body),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
            if (Math.floor(response.status / 100) !== 2) {
                alert('Слишком частая отправка данных. Следующая отправка станет доступна через 10 минут.')
            } else {
                document.getElementById('sent').style.visibility = 'visible'
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Заявка для получения демо-доступа в Контур<span style="color: limegreen">Фокус</span></h1>
    <label for="name-input">Ваше ФИО</label>
    <input type="text" placeholder="Ваше ФИО" id="name-input" size="40">
    <p id="name-input-error" class="error">Введите ваше ФИО</p>


    <label for="organisation-input">Организация</label>
    <input type="text" placeholder="Организация" id="organisation-input" size="40">
    <p class="error" id="organisation-input-error">Введите вашу организацию</p>


    <label for="position-input">Ваша должность</label>
    <input type="text" placeholder="Ваша должность" id="position-input" size="40">
    <p id="position-input-error" class="error">Введите вашу должность</p>


    <label for="phone-input">Номер телефона</label>
    <input type="text" placeholder="+375123456789" id="phone-input" size="40">
    <p id="phone-input-error" class="error">Введите ваш номер телефона</p>


    <label for="email-input">Email</label>
    <input type="email" placeholder="email@example.by" id="email-input" size="40">
    <p id="email-input-error" class="error">Введите ваш e-mail</p>

    <button onclick="onClick()">Отправить</button>
    <p id="sent">Спасибо за обращение! В ближайшее время с Вами свяжется наш специалист.</p>
</div>
</body>
</html>
