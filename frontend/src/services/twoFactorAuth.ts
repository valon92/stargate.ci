// Two-Factor Authentication Service
export interface TwoFactorSetup {
  secret: string;
  qrCode: string;
  backupCodes: string[];
}

export interface TwoFactorVerification {
  code: string;
  backupCode?: string;
}

export interface SecuritySettings {
  twoFactorEnabled: boolean;
  emailVerificationEnabled: boolean;
  passwordResetEnabled: boolean;
  sessionTimeout: number; // in minutes
  maxLoginAttempts: number;
  lockoutDuration: number; // in minutes
}

class TwoFactorAuthService {
  private readonly STORAGE_KEY = 'stargate_2fa_settings';
  private readonly BACKUP_CODES_KEY = 'stargate_2fa_backup_codes';

  // Generate a random secret for 2FA
  generateSecret(): string {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    let secret = '';
    for (let i = 0; i < 32; i++) {
      secret += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return secret;
  }

  // Generate QR code URL for authenticator apps
  generateQRCode(secret: string, email: string): string {
    const issuer = 'Stargate.ci';
    const accountName = email;
    const otpAuthUrl = `otpauth://totp/${encodeURIComponent(issuer)}:${encodeURIComponent(accountName)}?secret=${secret}&issuer=${encodeURIComponent(issuer)}`;
    
    // Using a QR code service (in production, use a proper QR code library)
    return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(otpAuthUrl)}`;
  }

  // Generate backup codes
  generateBackupCodes(): string[] {
    const codes: string[] = [];
    for (let i = 0; i < 10; i++) {
      const code = Math.random().toString(36).substring(2, 8).toUpperCase();
      codes.push(code);
    }
    return codes;
  }

  // Setup 2FA for a user
  setupTwoFactor(email: string): TwoFactorSetup {
    const secret = this.generateSecret();
    const qrCode = this.generateQRCode(secret, email);
    const backupCodes = this.generateBackupCodes();

    const setup: TwoFactorSetup = {
      secret,
      qrCode,
      backupCodes
    };

    // Store backup codes securely
    this.storeBackupCodes(backupCodes);

    return setup;
  }

  // Verify 2FA code
  verifyTwoFactorCode(secret: string, code: string): boolean {
    // Simple TOTP implementation (in production, use a proper library)
    const time = Math.floor(Date.now() / 1000 / 30);
    const expectedCode = this.generateTOTP(secret, time);
    
    // Also check previous and next time windows for clock drift
    const prevCode = this.generateTOTP(secret, time - 1);
    const nextCode = this.generateTOTP(secret, time + 1);
    
    return code === expectedCode || code === prevCode || code === nextCode;
  }

  // Verify backup code
  verifyBackupCode(code: string): boolean {
    const backupCodes = this.getBackupCodes();
    const index = backupCodes.indexOf(code);
    
    if (index > -1) {
      // Remove used backup code
      backupCodes.splice(index, 1);
      this.storeBackupCodes(backupCodes);
      return true;
    }
    
    return false;
  }

  // Generate TOTP code
  private generateTOTP(secret: string, time: number): string {
    // This is a simplified implementation
    // In production, use a proper TOTP library
    const key = this.base32Decode(secret);
    const timeBuffer = new ArrayBuffer(8);
    const timeView = new DataView(timeBuffer);
    timeView.setUint32(4, time, false);
    
    // Simplified HMAC-SHA1 implementation
    const hash = this.simpleHMAC(key, timeBuffer);
    const offset = hash[hash.length - 1] & 0xf;
    const code = ((hash[offset] & 0x7f) << 24) |
                 ((hash[offset + 1] & 0xff) << 16) |
                 ((hash[offset + 2] & 0xff) << 8) |
                 (hash[offset + 3] & 0xff);
    
    return (code % 1000000).toString().padStart(6, '0');
  }

  // Simplified base32 decode
  private base32Decode(encoded: string): Uint8Array {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    const result: number[] = [];
    
    for (let i = 0; i < encoded.length; i += 8) {
      let chunk = encoded.substring(i, i + 8);
      let value = 0;
      
      for (let j = 0; j < chunk.length; j++) {
        const charIndex = chars.indexOf(chunk[j]);
        if (charIndex === -1) continue;
        value = (value << 5) | charIndex;
      }
      
      // Convert to bytes
      for (let k = 0; k < Math.floor(chunk.length * 5 / 8); k++) {
        result.push((value >> (8 * (Math.floor(chunk.length * 5 / 8) - 1 - k))) & 0xff);
      }
    }
    
    return new Uint8Array(result);
  }

  // Simplified HMAC implementation
  private simpleHMAC(key: Uint8Array, data: ArrayBuffer): Uint8Array {
    // This is a very simplified implementation
    // In production, use a proper crypto library
    const combined = new Uint8Array(key.length + data.byteLength);
    combined.set(key);
    combined.set(new Uint8Array(data), key.length);
    
    // Simple hash (in production, use proper SHA-1)
    const hash = new Uint8Array(20);
    for (let i = 0; i < 20; i++) {
      hash[i] = combined[i % combined.length] ^ (i * 7);
    }
    
    return hash;
  }

  // Store backup codes
  private storeBackupCodes(codes: string[]): void {
    localStorage.setItem(this.BACKUP_CODES_KEY, JSON.stringify(codes));
  }

  // Get backup codes
  private getBackupCodes(): string[] {
    const stored = localStorage.getItem(this.BACKUP_CODES_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  // Enable 2FA for user
  enableTwoFactor(userId: string, secret: string): void {
    const settings = this.getSecuritySettings();
    settings.twoFactorEnabled = true;
    this.updateSecuritySettings(settings);
    
    // Store 2FA secret (in production, this should be encrypted)
    localStorage.setItem(`${this.STORAGE_KEY}_${userId}`, secret);
  }

  // Disable 2FA for user
  disableTwoFactor(userId: string): void {
    const settings = this.getSecuritySettings();
    settings.twoFactorEnabled = false;
    this.updateSecuritySettings(settings);
    
    localStorage.removeItem(`${this.STORAGE_KEY}_${userId}`);
  }

  // Check if 2FA is enabled
  isTwoFactorEnabled(userId: string): boolean {
    const settings = this.getSecuritySettings();
    return settings.twoFactorEnabled && !!localStorage.getItem(`${this.STORAGE_KEY}_${userId}`);
  }

  // Get security settings
  getSecuritySettings(): SecuritySettings {
    const stored = localStorage.getItem(this.STORAGE_KEY);
    if (stored) {
      return JSON.parse(stored);
    }
    
    // Default settings
    return {
      twoFactorEnabled: false,
      emailVerificationEnabled: false,
      passwordResetEnabled: true,
      sessionTimeout: 30, // 30 minutes
      maxLoginAttempts: 5,
      lockoutDuration: 15 // 15 minutes
    };
  }

  // Update security settings
  updateSecuritySettings(settings: SecuritySettings): void {
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(settings));
  }

  // Track login attempts
  trackLoginAttempt(email: string, success: boolean): void {
    const key = `login_attempts_${email}`;
    const attempts = JSON.parse(localStorage.getItem(key) || '[]');
    
    attempts.push({
      timestamp: Date.now(),
      success
    });
    
    // Keep only last 24 hours of attempts
    const dayAgo = Date.now() - (24 * 60 * 60 * 1000);
    const recentAttempts = attempts.filter((attempt: any) => attempt.timestamp > dayAgo);
    
    localStorage.setItem(key, JSON.stringify(recentAttempts));
  }

  // Check if account is locked
  isAccountLocked(email: string): boolean {
    const settings = this.getSecuritySettings();
    const key = `login_attempts_${email}`;
    const attempts = JSON.parse(localStorage.getItem(key) || '[]');
    
    const recentFailedAttempts = attempts.filter((attempt: any) => 
      !attempt.success && 
      (Date.now() - attempt.timestamp) < (settings.lockoutDuration * 60 * 1000)
    );
    
    return recentFailedAttempts.length >= settings.maxLoginAttempts;
  }

  // Get remaining backup codes
  getRemainingBackupCodes(): number {
    return this.getBackupCodes().length;
  }

  // Generate new backup codes
  generateNewBackupCodes(): string[] {
    const newCodes = this.generateBackupCodes();
    this.storeBackupCodes(newCodes);
    return newCodes;
  }
}

export const twoFactorAuth = new TwoFactorAuthService();
