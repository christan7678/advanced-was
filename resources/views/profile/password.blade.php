@extends('layouts.app')

@section('content')
    <div class="account-page">
        <section class="account-section">
            <div class="account-back">
                <a href="/profile" class="back-btn">
                    🡰 Back
                </a>  
            </div>

            <div class="account-section-label">Change Password</div>

            <div class="account-menu" style="padding: 20px;">
                <div style="max-width: 600px;">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #065f46; font-size: 14px;">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #7f1d1d; font-size: 14px;">
                            <strong>❌ Please fix the following errors:</strong>
                            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('profile.password') }}" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                        @csrf

                        <!-- Current Password -->
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                                Current Password
                            </label>
                            <input 
                                type="password" 
                                name="current_password" 
                                value="{{ old('current_password') }}"
                                style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                                placeholder="Enter your current password"
                                required
                            >
                            @error('current_password')
                                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                                New Password
                            </label>
                            <input 
                                type="password" 
                                name="password"
                                value="{{ old('password') }}"
                                style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                                placeholder="Enter your new password (min. 8 characters)"
                                required
                            >
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                                ℹ️ Password must be at least 8 characters long
                            </div>
                            @error('password')
                                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">
                                Confirm Password
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation"
                                style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                                placeholder="Re-enter your new password"
                                required
                            >
                            @error('password_confirmation')
                                <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            style="width: 100%; background: #059669; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer; transition: background 0.3s;"
                            onmouseover="this.style.background='#047857'"
                            onmouseout="this.style.background='#059669'"
                        >
                            🔒 Update Password
                        </button>
                    </form>

                    <!-- Security Tips -->
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; margin-top: 20px; color: #1e40af; font-size: 13px;">
                        <strong>🛡️ Security Tips:</strong>
                        <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                            <li>Use a strong password with letters, numbers, and symbols</li>
                            <li>Never share your password with anyone</li>
                            <li>Change your password regularly for security</li>
                            <li>If you suspect unauthorized access, change immediately</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection