<div id="registerModal" class="modal-overlay">
    <div class="modal-container">
        <button class="modal-close" onclick="closeModal('registerModal')">&times;</button>
        
        <h2>Register for Game Hub</h2>
        
        <?php if (!empty($register_error)): ?>
            <div class="error"><?php echo $register_error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="register">

            <div class="form-group">
                <label for="reg_username">Username:</label>
                <input type="text" id="reg_username" name="username" minlength="3" required>
            </div>

            <div class="form-group">
                <label for="reg_email">Email:</label>
                <input type="email" id="reg_email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="reg_password">Password:</label>
                <input type="password" id="reg_password" name="password" minlength="8" required>
            </div>

            <div class="form-group">
                <label for="server">Preferred Server Region:</label>
                <select name="server" id="server" required>
                    <option value="seas">Southeast Asia</option>
                    <option value="east">East Asia</option>
                    <option value="aus">Oceania</option>
                    <option value="mea">Middle East</option>
                    <option value="safrica">South Africa</option>
                    <option value="euwest">West Europe</option>
                    <option value="eunorth">North Europe</option>
                    <option value="nawest">West N. America</option>
                    <option value="naeast">East N. America</option>
                    <option value="nacentral">Central N. America</option>
                    <option value="southmerica">South Americas</option>
                </select>
            </div>

            <div class="form-group">
                <label for="prompt">Security Prompt (Question):</label>
                <select name="prompt" id="prompt" required>
                    <option value="prompt_1">What is love?</option>
                    <option value="prompt_2">Who will never give you up?</option>
                    <option value="prompt_3">Who is Franz Hermann?</option>
                    <option value="prompt_4">Who will win the 2025 Formula 1 World's Drivers Championship, and why is it Max Verstappen?</option>
                    <option value="prompt_5">How?</option>
                </select>
            </div>

            <div class="form-group">
                <label for="answer">Security Answer:</label>
                <input type="text" id="answer" name="answer" required>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>

        <div class="register-link">
            <p>Already have an account? <a href="#" onclick="switchToModal('registerModal', 'loginModal')">Login here</a></p>
        </div>
    </div>
</div>