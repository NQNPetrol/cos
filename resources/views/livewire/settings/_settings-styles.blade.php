<style>
/* Modern Settings Styles - Dark Theme */
.modern-settings-container {
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

.modern-settings-header {
    margin-bottom: 32px;
}

.modern-settings-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--fb-text-primary);
    margin-bottom: 8px;
}

.modern-settings-subtitle {
    font-size: 14px;
    color: var(--fb-text-secondary);
}

.modern-settings-layout {
    display: flex;
    gap: 32px;
}

.modern-settings-nav {
    width: 220px;
    flex-shrink: 0;
}

.modern-settings-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    color: var(--fb-text-secondary);
    text-decoration: none;
    margin-bottom: 4px;
    transition: all 0.2s ease;
}

.modern-settings-nav-item:hover {
    background-color: var(--fb-bg-tertiary);
    color: var(--fb-text-primary);
}

.modern-settings-nav-item.active {
    background-color: var(--fb-accent-blue);
    color: white;
}

.modern-settings-content {
    flex: 1;
    min-width: 0;
}

.modern-settings-section {
    background-color: var(--fb-bg-secondary);
    border: 1px solid var(--fb-border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.modern-settings-section-header {
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--fb-border);
}

.modern-settings-section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--fb-text-primary);
    margin-bottom: 4px;
}

.modern-settings-section-desc {
    font-size: 14px;
    color: var(--fb-text-secondary);
}

.modern-settings-form {
    max-width: 500px;
}

.modern-form-group {
    margin-bottom: 20px;
}

.modern-form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--fb-text-primary);
    margin-bottom: 8px;
}

.modern-form-input {
    width: 100%;
    padding: 10px 14px;
    background-color: var(--fb-bg-tertiary);
    border: 1px solid var(--fb-border);
    border-radius: 8px;
    color: var(--fb-text-primary);
    font-size: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.modern-form-input:focus {
    outline: none;
    border-color: var(--fb-accent-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modern-form-error {
    display: block;
    color: #ef4444;
    font-size: 13px;
    margin-top: 6px;
}

.modern-form-success {
    color: #10b981;
    font-size: 14px;
}

.modern-form-warning {
    margin-top: 12px;
    padding: 12px;
    background-color: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 8px;
    font-size: 14px;
    color: var(--fb-text-secondary);
}

.modern-form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 24px;
}

.modern-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
}

.modern-btn-primary {
    background-color: var(--fb-accent-blue);
    color: white;
}

.modern-btn-primary:hover {
    background-color: #2563eb;
}

.modern-btn-secondary {
    background-color: var(--fb-bg-tertiary);
    color: var(--fb-text-primary);
    border: 1px solid var(--fb-border);
}

.modern-btn-secondary:hover {
    background-color: var(--fb-bg-primary);
}

.modern-btn-ghost {
    background-color: transparent;
    color: var(--fb-text-secondary);
}

.modern-btn-ghost:hover {
    background-color: var(--fb-bg-tertiary);
    color: var(--fb-text-primary);
}

.modern-btn-danger {
    background-color: #dc2626;
    color: white;
}

.modern-btn-danger:hover {
    background-color: #b91c1c;
}

.modern-link {
    color: var(--fb-accent-blue);
    cursor: pointer;
    background: none;
    border: none;
    font-size: inherit;
}

.modern-link:hover {
    text-decoration: underline;
}

.modern-loading {
    color: var(--fb-text-secondary);
    font-size: 14px;
}

/* Photo Upload */
.modern-photo-upload {
    display: flex;
    align-items: flex-start;
    gap: 24px;
}

.modern-photo-preview {
    position: relative;
}

.modern-avatar-lg {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--fb-border);
}

.modern-avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--fb-bg-tertiary);
    color: var(--fb-text-primary);
    font-size: 32px;
    font-weight: 700;
}

.modern-photo-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    overflow: hidden;
}

.modern-photo-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.modern-photo-hint {
    font-size: 13px;
    color: var(--fb-text-secondary);
}

/* Sessions */
.modern-sessions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.modern-session-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background-color: var(--fb-bg-tertiary);
    border-radius: 8px;
}

.modern-session-icon {
    color: var(--fb-text-secondary);
}

.modern-session-info {
    flex: 1;
}

.modern-session-device {
    font-size: 14px;
    font-weight: 500;
    color: var(--fb-text-primary);
}

.modern-session-details {
    font-size: 13px;
    color: var(--fb-text-secondary);
}

.modern-session-current {
    color: #10b981;
    font-weight: 500;
}

.modern-sessions-empty {
    color: var(--fb-text-secondary);
    font-size: 14px;
}

/* Modal */
.modern-modal {
    background-color: var(--fb-bg-secondary);
    border: 1px solid var(--fb-border);
    border-radius: 12px;
    padding: 0;
    max-width: 450px;
    width: 90%;
}

.modern-modal::backdrop {
    background-color: rgba(0, 0, 0, 0.7);
}

.modern-modal-content {
    padding: 24px;
}

.modern-modal-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--fb-text-primary);
    margin-bottom: 8px;
}

.modern-modal-title.modern-modal-danger {
    color: #ef4444;
}

.modern-modal-desc {
    font-size: 14px;
    color: var(--fb-text-secondary);
    margin-bottom: 20px;
}

.modern-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}

/* Danger Section */
.modern-settings-danger {
    border-color: rgba(239, 68, 68, 0.3);
}

.modern-settings-danger .modern-settings-section-header {
    border-bottom-color: rgba(239, 68, 68, 0.3);
}

/* Info Card */
.modern-info-card {
    background-color: var(--fb-bg-tertiary);
    border-radius: 12px;
    padding: 20px;
}

.modern-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.modern-info-item label {
    display: block;
    font-size: 13px;
    color: var(--fb-text-secondary);
    margin-bottom: 4px;
}

.modern-info-item p {
    font-size: 15px;
    font-weight: 500;
    color: var(--fb-text-primary);
    margin: 0;
}

/* Support Card */
.modern-support-card {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 12px;
    padding: 24px;
}

.modern-support-content {
    display: flex;
    gap: 20px;
}

.modern-support-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background-color: var(--fb-accent-blue);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.modern-support-info {
    flex: 1;
}

.modern-support-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--fb-text-primary);
    margin-bottom: 8px;
}

.modern-support-desc {
    font-size: 14px;
    color: var(--fb-text-secondary);
    margin-bottom: 16px;
    line-height: 1.6;
}

.modern-support-email {
    font-size: 13px;
    color: var(--fb-text-secondary);
    margin-top: 12px;
}

/* Quick Links */
.modern-quick-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.modern-quick-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background-color: var(--fb-bg-tertiary);
    border: 1px solid var(--fb-border);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.modern-quick-link:hover {
    background-color: var(--fb-bg-primary);
    border-color: var(--fb-accent-blue);
}

.modern-quick-link-icon {
    color: var(--fb-text-secondary);
}

.modern-quick-link-text {
    flex: 1;
}

.modern-quick-link-title {
    font-size: 14px;
    font-weight: 500;
    color: var(--fb-text-primary);
}

.modern-quick-link-desc {
    font-size: 12px;
    color: var(--fb-text-secondary);
}

/* Responsive */
@media (max-width: 768px) {
    .modern-settings-layout {
        flex-direction: column;
    }

    .modern-settings-nav {
        width: 100%;
        display: flex;
        overflow-x: auto;
        gap: 8px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--fb-border);
        margin-bottom: 24px;
    }

    .modern-settings-nav-item {
        white-space: nowrap;
    }

    .modern-photo-upload {
        flex-direction: column;
        align-items: center;
    }
    
    .modern-info-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-support-content {
        flex-direction: column;
    }
}
</style>

