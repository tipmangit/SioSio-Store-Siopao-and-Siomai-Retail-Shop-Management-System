// PHP Email Service Configuration
const EmailJSOTPService = {
    // Send registration OTP using PHP
    async sendRegistrationOTP(email, otp, name) {
        try {
            const response = await fetch('email-sender.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    name: name,
                    otp: otp,
                    type: 'registration'
                })
            });
            
            const result = await response.json();
            console.log('Registration OTP result:', result);
            return result;
            
        } catch (error) {
            console.error('Failed to send registration OTP:', error);
            return { success: false, message: 'Failed to send OTP' };
        }
    },

    // Send password reset OTP using PHP
    async sendPasswordResetOTP(email, otp, name) {
        try {
            const response = await fetch('email-sender.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    name: name,
                    otp: otp,
                    type: 'password_reset'
                })
            });
            
            const result = await response.json();
            console.log('Password reset OTP result:', result);
            return result;
            
        } catch (error) {
            console.error('Failed to send password reset OTP:', error);
            return { success: false, message: 'Failed to send OTP' };
        }
    },

    // Utility functions for UI feedback
    showLoading(button) {
        button.disabled = true;
        button.textContent = 'Sending...';
        button.style.opacity = '0.7';
    },

    hideLoading(button, originalText) {
        button.disabled = false;
        button.textContent = originalText;
        button.style.opacity = '1';
    },

    showSuccess(message) {
        this.showNotification(message, 'success');
    },

    showError(message) {
        this.showNotification(message, 'error');
    },

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `${type}-popup`;
        notification.innerHTML = `
            <div class="${type}-popup-content">
                <h3>${type === 'success' ? 'Success!' : 'Error:'}</h3>
                <p>${message}</p>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 500);
        }, 4000);
    }
};