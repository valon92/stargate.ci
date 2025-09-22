import { backendApi } from './backendApi'

export interface UploadedFile {
  id: number
  filename: string
  original_name: string
  url: string
  type: string
  size: number
  mime_type: string
  uploaded_at: string
}

export interface UploadOptions {
  type: 'image' | 'video' | 'audio' | 'document'
  context?: string
  contextId?: number
  onProgress?: (progress: number) => void
}

export interface FileUploadResponse {
  success: boolean
  message: string
  data?: UploadedFile | UploadedFile[]
  errors?: any[]
}

class FileUploadService {
  private baseUrl: string

  constructor() {
    this.baseUrl = backendApi.getApiBaseUrl()
  }

  /**
   * Upload a single file
   */
  async uploadFile(file: File, options: UploadOptions): Promise<FileUploadResponse> {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('type', options.type)
    
    if (options.context) {
      formData.append('context', options.context)
    }
    
    if (options.contextId) {
      formData.append('context_id', options.contextId.toString())
    }

    try {
      const xhr = new XMLHttpRequest()
      
      return new Promise((resolve, reject) => {
        xhr.upload.addEventListener('progress', (event) => {
          if (event.lengthComputable && options.onProgress) {
            const progress = Math.round((event.loaded / event.total) * 100)
            options.onProgress(progress)
          }
        })

        xhr.addEventListener('load', () => {
          try {
            const response = JSON.parse(xhr.responseText)
            if (xhr.status >= 200 && xhr.status < 300) {
              resolve(response)
            } else {
              reject(new Error(response.message || 'Upload failed'))
            }
          } catch (error) {
            reject(new Error('Invalid server response'))
          }
        })

        xhr.addEventListener('error', () => {
          reject(new Error('Network error during upload'))
        })

        xhr.open('POST', `${this.baseUrl}/files/upload`)
        
        const token = backendApi.getAuthToken()
        if (token) {
          xhr.setRequestHeader('Authorization', `Bearer ${token}`)
        }

        xhr.send(formData)
      })
    } catch (error: any) {
      throw new Error(error.message || 'Upload failed')
    }
  }

  /**
   * Upload multiple files
   */
  async uploadMultipleFiles(files: File[], options: UploadOptions): Promise<FileUploadResponse> {
    const formData = new FormData()
    
    files.forEach((file) => {
      formData.append('files[]', file)
    })
    
    formData.append('type', options.type)
    
    if (options.context) {
      formData.append('context', options.context)
    }
    
    if (options.contextId) {
      formData.append('context_id', options.contextId.toString())
    }

    try {
      const xhr = new XMLHttpRequest()
      
      return new Promise((resolve, reject) => {
        xhr.upload.addEventListener('progress', (event) => {
          if (event.lengthComputable && options.onProgress) {
            const progress = Math.round((event.loaded / event.total) * 100)
            options.onProgress(progress)
          }
        })

        xhr.addEventListener('load', () => {
          try {
            const response = JSON.parse(xhr.responseText)
            if (xhr.status >= 200 && xhr.status < 300) {
              resolve(response)
            } else {
              reject(new Error(response.message || 'Upload failed'))
            }
          } catch (error) {
            reject(new Error('Invalid server response'))
          }
        })

        xhr.addEventListener('error', () => {
          reject(new Error('Network error during upload'))
        })

        xhr.open('POST', `${this.baseUrl}/files/upload-multiple`)
        
        const token = backendApi.getAuthToken()
        if (token) {
          xhr.setRequestHeader('Authorization', `Bearer ${token}`)
        }

        xhr.send(formData)
      })
    } catch (error: any) {
      throw new Error(error.message || 'Upload failed')
    }
  }

  /**
   * Get user's uploaded files
   */
  async getUserFiles(options?: {
    type?: 'image' | 'video' | 'audio' | 'document'
    context?: string
    page?: number
    perPage?: number
  }): Promise<{ success: boolean; data: any }> {
    try {
      const params = new URLSearchParams()
      
      if (options?.type) params.append('type', options.type)
      if (options?.context) params.append('context', options.context)
      if (options?.page) params.append('page', options.page.toString())
      if (options?.perPage) params.append('per_page', options.perPage.toString())

      const response = await fetch(`${this.baseUrl}/files?${params.toString()}`, {
        headers: {
          'Authorization': `Bearer ${backendApi.getAuthToken()}`,
          'Accept': 'application/json',
        },
      })

      const result = await response.json()
      
      if (!response.ok) {
        throw new Error(result.message || 'Failed to fetch files')
      }

      return result
    } catch (error: any) {
      throw new Error(error.message || 'Failed to fetch files')
    }
  }

  /**
   * Delete a file
   */
  async deleteFile(fileId: number): Promise<{ success: boolean; message: string }> {
    try {
      const response = await fetch(`${this.baseUrl}/files/${fileId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${backendApi.getAuthToken()}`,
          'Accept': 'application/json',
        },
      })

      const result = await response.json()
      
      if (!response.ok) {
        throw new Error(result.message || 'Failed to delete file')
      }

      return result
    } catch (error: any) {
      throw new Error(error.message || 'Failed to delete file')
    }
  }

  /**
   * Validate file before upload
   */
  validateFile(file: File, options: UploadOptions & { maxSize?: number }): { valid: boolean; error?: string } {
    const maxSize = options.maxSize || 10 // Default 10MB
    
    // Check file size
    if (file.size > maxSize * 1024 * 1024) {
      return {
        valid: false,
        error: `File size exceeds ${maxSize}MB limit`
      }
    }

    // Check file type
    const allowedTypes = this.getAllowedTypes(options.type)
    const isValidType = allowedTypes.some(type => {
      if (type.startsWith('.')) {
        return file.name.toLowerCase().endsWith(type.toLowerCase())
      } else {
        return file.type === type || file.type.startsWith(type.replace('/*', '/'))
      }
    })

    if (!isValidType) {
      return {
        valid: false,
        error: `Invalid file type. Allowed: ${this.getTypeDescription(options.type)}`
      }
    }

    return { valid: true }
  }

  /**
   * Get allowed file types for upload type
   */
  private getAllowedTypes(uploadType: string): string[] {
    switch (uploadType) {
      case 'image':
        return ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']
      case 'video':
        return ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/webm', 'video/x-msvideo']
      case 'audio':
        return ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4', 'audio/webm']
      case 'document':
        return [
          'application/pdf',
          '.doc', '.docx',
          '.xls', '.xlsx',
          '.txt', '.csv'
        ]
      default:
        return ['*']
    }
  }

  /**
   * Get human-readable description of allowed file types
   */
  private getTypeDescription(uploadType: string): string {
    switch (uploadType) {
      case 'image':
        return 'PNG, JPG, GIF, WebP, SVG'
      case 'video':
        return 'MP4, MPEG, MOV, WebM, AVI'
      case 'audio':
        return 'MP3, WAV, OGG, MP4, WebM'
      case 'document':
        return 'PDF, DOC, DOCX, XLS, XLSX, TXT, CSV'
      default:
        return 'All file types'
    }
  }

  /**
   * Format file size for display
   */
  formatFileSize(bytes: number): string {
    const sizes = ['B', 'KB', 'MB', 'GB']
    if (bytes === 0) return '0 B'
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i]
  }

  /**
   * Get file type icon
   */
  getFileTypeIcon(type: string): string {
    switch (type) {
      case 'image':
        return '🖼️'
      case 'video':
        return '🎥'
      case 'audio':
        return '🎵'
      case 'document':
        return '📄'
      default:
        return '📁'
    }
  }
}

export const fileUploadService = new FileUploadService()
