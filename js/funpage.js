// ------------------------------------------------------
// GAME DATA: Sentences for each level of the word game
// Each entry contains:
// - the correct sentence
// - an array of words
// - an (initially empty) array for shuffled words
// ------------------------------------------------------
const GAME_SENTENCES = [
    {
        sentence: "Book your perfect hotel stay today",
        words: ["Book", "your", "perfect", "hotel", "stay", "today"],
        shuffled: []
    },
    {
        sentence: "Find amazing hotels in beautiful Oman",
        words: ["Find", "amazing", "hotels", "in", "beautiful", "Oman"],
        shuffled: []
    },
    {
        sentence: "Enjoy luxury accommodations at great prices",
        words: ["Enjoy", "luxury", "accommodations", "at", "great", "prices"],
        shuffled: []
    },
    {
        sentence: "Check in and check out with ease",
        words: ["Check", "in", "and", "check", "out", "with", "ease"],
        shuffled: []
    },
    {
        sentence: "Booklify offers the best hotel booking experience",
        words: ["Booklify", "offers", "the", "best", "hotel", "booking", "experience"],
        shuffled: []
    }
];

// ---------------------------------------
// GLOBAL GAME STATE VARIABLES
// ---------------------------------------
let currentLevel = 0;
let score = 0;
let completedSentences = 0;
let currentSentence = [];
let targetSentence = "";
let availableWords = [];

// ------------------------------------------------------------
// Utility: Shuffle an array (Fisher–Yates shuffle algorithm)
// Returns a new shuffled version of the array
// ------------------------------------------------------------
function shuffleArray(array) {
    const shuffled = [...array];
    
    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        const temp = shuffled[i];
        shuffled[i] = shuffled[j];
        shuffled[j] = temp;
    }
    
    return shuffled;
}

// ------------------------------------------------------------
// Initializes a NEW GAME (reset all progress)
// ------------------------------------------------------------
function startNewGame() {
    currentLevel = 0;
    score = 0;
    completedSentences = 0;
    currentSentence = [];
    
    updateScore();
    updateLevel();
    updateCompleted();
    
    loadLevel(currentLevel);
}

// ------------------------------------------------------------
// Loads a specific level's target sentence and shuffled words
// ------------------------------------------------------------
function loadLevel(level) {
    // If player finished all levels → show final screen
    if (level >= GAME_SENTENCES.length) {
        showGameComplete();
        return;
    }
    
    const sentenceData = GAME_SENTENCES[level];
    targetSentence = sentenceData.sentence;
    
    // Shuffle the available words for this level
    availableWords = shuffleArray(sentenceData.words);
    
    currentSentence = [];
    
    displayTargetSentence();
    displayWords();
    clearSentenceArea();
}

// Shows the target sentence (the solution the user must match)
function displayTargetSentence() {
    const targetElement = document.getElementById('targetSentence');
    targetElement.innerHTML = `<strong>Target:</strong> "${targetSentence}"`;
}

// ------------------------------------------------------------
// Displays clickable word tiles for the player to pick from
// ------------------------------------------------------------
function displayWords() {
    const container = document.getElementById('wordsContainer');
    container.innerHTML = '';
    
    availableWords.forEach((word, index) => {
        const wordTile = document.createElement('div');
        wordTile.className = 'word-tile';
        wordTile.textContent = word;
        
        // Store index + usage status in dataset
        wordTile.dataset.wordIndex = index;
        wordTile.dataset.used = 'false';
        
        // When clicked, add word to user-built sentence
        wordTile.addEventListener('click', function() {
            addWordToSentence(word, index);
        });
        
        container.appendChild(wordTile);
    });
}

// ------------------------------------------------------------
// Adds a selected word to the sentence-building area
// ------------------------------------------------------------
function addWordToSentence(word, wordIndex) {
    const wordTile = document.querySelector(`[data-word-index="${wordIndex}"]`);
    
    // Prevent using same word tile twice
    if (wordTile.dataset.used === 'true') {
        return;
    }
    
    currentSentence.push({
        word: word,
        index: wordIndex
    });
    
    // Mark tile as used
    wordTile.dataset.used = 'true';
    wordTile.classList.add('used');
    
    updateSentenceArea();
}

// ------------------------------------------------------------
// Updates the sentence area where the player's words appear
// ------------------------------------------------------------
function updateSentenceArea() {
    const sentenceArea = document.getElementById('sentenceArea');
    sentenceArea.innerHTML = '';
    
    // Show hint when empty
    if (currentSentence.length === 0) {
        sentenceArea.innerHTML = '<p class="text-muted text-center">Click words below to build your sentence...</p>';
        return;
    }
    
    // Create each word block with a remove (×) button
    currentSentence.forEach((wordData, position) => {
        const wordSpan = document.createElement('span');
        wordSpan.className = 'sentence-word';
        wordSpan.textContent = wordData.word;
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '×';
        
        // Clicking × removes the word
        removeBtn.onclick = function() {
            removeWordFromSentence(position);
        };
        
        wordSpan.appendChild(removeBtn);
        sentenceArea.appendChild(wordSpan);
    });
}

// ------------------------------------------------------------
// Removes a word from the user's sentence
// ------------------------------------------------------------
function removeWordFromSentence(position) {
    const removedWord = currentSentence[position];
    
    currentSentence.splice(position, 1);
    
    // Re-enable the clickable tile
    const wordTile = document.querySelector(`[data-word-index="${removedWord.index}"]`);
    if (wordTile) {
        wordTile.dataset.used = 'false';
        wordTile.classList.remove('used');
    }
    
    updateSentenceArea();
}

// Clears the entire built sentence (reset)
function clearSentence() {
    currentSentence.forEach(wordData => {
        const wordTile = document.querySelector(`[data-word-index="${wordData.index}"]`);
        if (wordTile) {
            wordTile.dataset.used = 'false';
            wordTile.classList.remove('used');
        }
    });
    
    currentSentence = [];
    updateSentenceArea();
}

// ------------------------------------------------------------
// Checks if the built sentence matches the target solution
// ------------------------------------------------------------
function checkSentence() {
    const builtSentence = currentSentence.map(w => w.word).join(' ');
    
    if (builtSentence.toLowerCase() === targetSentence.toLowerCase()) {
        score += 100;
        completedSentences++;
        
        updateScore();
        updateCompleted();
        
        showSuccessMessage();
        
        // Move to next level with delay
        setTimeout(function() {
            currentLevel++;
            updateLevel();
            loadLevel(currentLevel);
        }, 2000);
    } else {
        showErrorMessage();
    }
}

// Shows green success alert
function showSuccessMessage() {
    const sentenceArea = document.getElementById('sentenceArea');
    const originalContent = sentenceArea.innerHTML;
    
    sentenceArea.innerHTML = `
        <div class="alert alert-success text-center">
            <h4>✓ Correct! Great job!</h4>
            <p>Moving to next level...</p>
        </div>`;
    
    setTimeout(() => {
        sentenceArea.innerHTML = originalContent;
    }, 2000);
}

// Shows red error alert
function showErrorMessage() {
    const sentenceArea = document.getElementById('sentenceArea');
    const originalContent = sentenceArea.innerHTML;
    
    sentenceArea.innerHTML = `
        <div class="alert alert-danger text-center">
            <h4>✗ Not quite right. Try again!</h4>
            <p>Check the word order and spelling.</p>
        </div>`;
    
    setTimeout(() => {
        sentenceArea.innerHTML = originalContent;
    }, 2000);
}

// ------------------------------------------------------------
// Final screen when all levels are completed
// ------------------------------------------------------------
function showGameComplete() {
    const sentenceArea = document.getElementById('sentenceArea');
    sentenceArea.innerHTML = `
        <div class="alert alert-success text-center">
            <h3>🎉 Congratulations! 🎉</h3>
            <p>You completed all levels!</p>
            <p><strong>Final Score: ${score}</strong></p>
            <button class="btn btn-primary mt-3" onclick="startNewGame()">Play Again</button>
        </div>
    `;
}

// UI counters
function updateScore() {
    document.getElementById('score').textContent = score;
}

function updateLevel() {
    document.getElementById('level').textContent = currentLevel + 1;
}

function updateCompleted() {
    document.getElementById('completed').textContent = completedSentences;
}

// ------------------------------------------------------------
// RUN when the page finishes loading
// ------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    startNewGame(); // Start the game immediately
    
    // Show instructions modal ONCE per session
    const instructionsShown = sessionStorage.getItem('instructionsShown');
    if (!instructionsShown) {
        const modal = new bootstrap.Modal(document.getElementById('instructionsModal'));
        modal.show();
        sessionStorage.setItem('instructionsShown', 'true');
    }
});
