declare module '@valon92/voice-actions-sdk' {
  export interface VoiceCommand {
    id: string
    action: string
    phrases: string[]
    category?: string
  }

  export interface VoiceActionsSDKOptions {
    apiKey?: string
    apiUrl?: string
    platform?: string
    locale?: string
    onCommand?: (command: VoiceCommand) => void
    onError?: (error: Error) => void
    debug?: boolean
  }

  export interface MicrophonePermission {
    granted: boolean
    state?: string
    error?: string
    message?: string
  }

  export default class VoiceActionsSDK {
    constructor(options?: VoiceActionsSDKOptions)
    
    start(): Promise<void>
    stop(): void
    setLocale(locale: string): void
    addCommand(command: VoiceCommand): void
    removeCommand(commandId: string): void
    checkMicrophonePermission(): Promise<MicrophonePermission>
    destroy(): void
    isSupported(): boolean
  }
}

