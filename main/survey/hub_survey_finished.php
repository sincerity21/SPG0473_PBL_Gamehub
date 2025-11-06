<!-- 
This file is a modal template. 
It is included by 'hub_survey_site.php'.
It requires the $game_link variable to be set by the parent page.
-->
<div id="surveyFinishedModal" class="modal-overlay">
    <div class="modal-container">
        <!-- We don't have a close button, user must choose an action -->
        
        <h2>Thank you for your feedback!</h2>
        
        <p>Your response has been recorded. What would you like to do next?</p>

        <div class="modal-buttons">
            <a href="../logged_in/hub_home_logged_in.php" class="modal-btn secondary">Home</a>
            <a href="<?php echo htmlspecialchars($game_link); ?>" target="_blank" class="modal-btn">Go to Game</a>
        </div>
    </div>
</div>