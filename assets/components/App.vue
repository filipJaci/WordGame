<template>
    <v-app>
        <v-container>
            <!-- Heading -->
            <v-row>
                <h1 class="text-h3 text-sm-h2 text-md-h1 text-center my-5 d-block">Welcome to the Word game! </h1>
            </v-row>

            <!-- Instructions -->
            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-alert
                        border="left"
                        color="indigo"
                        dark
                    >
                        <p class="my-1">The rules are simple:</p>
                        <ul>
                            <li>Write an actual English word;</li>
                            <li>You'l get a point for each unique character;</li>
                            <li>Three points if the word is an palindrome; and</li>
                            <li>Two points if you were close to making a palindrome, you've missed it by one character.</li>
                            <li>You may only use <strong>letter</strong>, <strong>digit</strong>, <strong>!</strong>, <strong>&</strong> or <strong>-</strong> characters.</li>
                        </ul>
                        <p class="my-1">Want to give it a shot? Try to pass a Word into the input field below, once you're ready press the submit button to validate. Good luck!</p>
                    </v-alert>
                </v-col>
                
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-container fill-height>
                        <!-- Score -->
                        <v-row>
                            <v-col  class="text-h3 text-sm-h2 text-md-h1 text-center">
                                {{ score }}
                            </v-col>
                        </v-row>
                        <!-- Input -->
                        <v-row class="text-center">
                            <v-text-field
                                label="Main input"
                                v-model="word"
                                hide-details="auto"
                            ></v-text-field>
                        </v-row>
                        <!-- Button -->
                        <v-row class="text-center">
                            <v-col>
                                <v-btn
                                    @click="hanldeOnClick"
                                    x-large
                                    depressed
                                    color="success"
                                    :disabled="buttonDisabled"
                                >
                                    Validate word
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
            </v-row>

            <!-- Results -->
            <v-row>
                <v-col>
                    <v-alert
                        :type="alertType"
                        transition="slide-y-transition"
                        v-if="alertDisplayed"
                    >
                        <ul>
                            <li
                                v-for="(message, key) in apiMessages"
                                :key="key"
                            >
                                {{ message.message }}
                            </li>
                        </ul>
                    </v-alert>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
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
                // Displays current score.
                score: 0,
                // Alert should be displayed.
                alertDisplayed: false,
                // Alert type.
                alertType: '',
                // Word that was previously validated.
                previousWord: '',
            };
        },
        methods: {
            hanldeOnClick()
            {
                // Validation isn't validating the same word again.
                if(this.currentAndPreviousWordDontMatch())
                {
                    // Reset validation fields.
                    this.resetValidationFileds();
                    // Word is in a valid format.
                    if(this.checkInputFormat())
                    {
                        // Make validation request.
                        this.makeRequest();
                    }
                    // Word is in an invalid format.
                    else{
                        alert('Your word didn\'t pass the validation. You may only use letter, digit, !, & or - characters.');
                    }
                }

            },
            resetValidationFileds()
            {
                // Disable button.
                this.toggleDisabledButton(true);
                // Reset past API responses.
                this.apiMessages = [];
                // Reset alert should be displayed.
                this.alertDisplayed = false;
                // Reset alert type.
                this.alertType = '';
            },
            currentAndPreviousWordDontMatch()
            {
                // Words don't match.
                return this.previousWord != this.word;
            },
            checkInputFormat()
            {
                // Regex used for validation.
                let validationRegex = /^[a-zA-Z0-9!&-]*$/;
                // Test through regex.
                return validationRegex.test(this.word);
            },
            toggleDisabledButton(disabled)
            {
                // Disable button.
                this.buttonDisabled = disabled;
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
                // Set word as just validated.
                this.previousWord = this.word;            
            },
            handleSuccess(response)
            {
                // Set alert type.
                this.alertType = 'success';
                // Get response messages.
                this.apiMessages = response.data.messages;
                // Display alerts.
                this.alertDisplayed = true;
                // Write word inside of Console.
                console.log('Scores for the word: ' + this.word);
                // Itterate through messages.
                for (const key in this.apiMessages) {
                    // Write messages inside of Console.
                    console.log(key, this.apiMessages[key].points);
                }
                // Set display score.
                this.score = this.apiMessages.total.points;
                // Enable button.
                this.buttonDisabled = false;

            },
            handleFaliure(error)
            {
                // Set alert type.
                this.alertType = 'error';
                // Get response messages.
                this.apiMessages = error.response.data.messages;
                // Display alerts.
                this.alertDisplayed = true;
                // Write word inside of Console.
                console.log('Scores for the word: ' + this.word);
                // Itterate through messages.
                for (const key in this.apiMessages) {
                    // Write messages inside of Console.
                    console.log(this.apiMessages[key]['message']);
                }
                // Reset display score.
                this.score = 0;
                // Enable button.
                this.buttonDisabled = false;
            },
        },
    };
</script>