
function validateAiForm() {
    var topicInput = document.querySelector("#ai_topic");
    var countInput = document.querySelector("#ai_count");
    var pointsInput = document.querySelector("#ai_points");
    var statusDiv = document.querySelector("#ai-status");

    statusDiv.textContent = "";
    statusDiv.style.color = "red";
    topicInput.style.border = "1px solid #ccc";
    countInput.style.border = "1px solid #ccc";
    if(pointsInput) pointsInput.style.border = "1px solid #ccc";

    // 3. Validate Topic (Title)
    var topicValue = topicInput.value.trim();
    if (topicValue === "") {
        statusDiv.textContent = "The Topic is required.";
        topicInput.style.border = "2px solid red";
        return false; 
    }
    if (topicValue.length < 3) {
        statusDiv.textContent = "Topic must be at least 3 characters.";
        topicInput.style.border = "2px solid red";
        return false;
    }

    var countValue = parseInt(countInput.value);
    if (isNaN(countValue) || countValue < 3 || countValue > 10) {
        statusDiv.textContent = "Number of questions must be between 3 and 10.";
        countInput.style.border = "2px solid red";
        return false;
    }

    if(pointsInput) {
        var pointsValue = parseInt(pointsInput.value);
        if (isNaN(pointsValue) || pointsValue < 1) {
            statusDiv.textContent = "Points must be at least 1.";
            pointsInput.style.border = "2px solid red";
            return false;
        }
    }

    return true;
}