<template>
    <div>
        <!-- Instructions -->
        <h1>Welcome to the Word game! </h1>
        <p>The rules are simple:</p>
        <ul>
            <li>Write an actual English word;</li>
            <li>You'l get a point for each unique character;</li>
            <li>Three points if the word is an palindrome; and</li>
            <li>Two points if you were close to making a palindrome, you've missed it by one character.</li>
            <li>You may only use <strong>letter</strong>, <strong>digit</strong>, <strong>!</strong>, <strong>&</strong> or <strong>-</strong> characters.</li>
        </ul>
        <p>Want to give it a shot? Try to pass a Word into the input field below, once you're ready press the submit button to validate. Good luck!</p>

        <!-- Input -->
        <input type="text" v-model="word" placeholder="Type your word here :)">
        <button @click="hanldeOnClick">Validate word!</button>

        <!-- Results -->
        <p>{{ results }}</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                // Word that will be validated.
                word: '',
                // Results of the validation.
                results: '',
                // Button is disabled.
                buttonDisabled: false,
            };
        },
        methods: {
            hanldeOnClick()
            {
                // Word is in a valid format.
                if(this.checkInputFormat())
                {
                    // Disable validate button.
                    this.disableButton();
                    // Make validation request.
                    this.makeRequest();
                }
            },
            checkInputFormat()
            {
                // Regex used for validation.
                let validationRegex = /^[a-zA-Z0-9!&-]*$/;
                // Test through regex.
                return validationRegex.test(this.word);
            },
            disableButton()
            {
                // Disable button.
                this.buttonDisabled = true;
            },
            makeRequest()
            {
                // Setup parameters.
                let parameters = new FormData();
                parameters.append ('word', this.word);
                // Make request.
                axios.post(
                    '/api/word',
                    parameters
                    ,
                    {
                        headers:
                        {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                )
                .then(response => {
                    console.log(response);
                })
                .catch(error => {
                    console.log(error);
                })
            }
        },
        mounted() {

        }
    };
</script>