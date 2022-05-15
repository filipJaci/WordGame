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
        <ul>
            <li
                v-for="(message, key) in apiMessages"
                :key="key"
            >
                {{ message.message }}
            </li>
        </ul>
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
                // Used for displaying API responses.
                apiMessages: '',
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
                    // Handle 2xx response.
                    this.handleSuccess(response);
                })
                .catch(error => {
                    // Handle a non 2xx response.
                    this.handleFaliure(error);
                })
            },
            handleSuccess(response)
            {
                // Get response messages.
                this.apiMessages = response.data.messages;
                // Write word inside of Console.
                console.log('Scores for the word: ' + this.word);
                // Itterate through messages.
                for (const key in this.apiMessages) {
                    // Write messages inside of Console.
                    console.log(key, this.apiMessages[key].points);
                }
            },
            handleFaliure(error)
            {
                // Get response messages.
                this.apiMessages = error.response.data.messages;
                console.log(this.apiMessages);
                // Write word inside of Console.
                console.log('Scores for the word: ' + this.word);
                // Itterate through messages.
                for (const key in this.apiMessages) {
                    // Write messages inside of Console.
                    console.log(this.apiMessages[key]['message']);
                }
            }
        },
        mounted() {

        }
    };
</script>