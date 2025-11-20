// Lightweight API client for frontend services

type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'

export interface ApiClientOptions extends RequestInit {
  headers?: Record<string, string>
  json?: unknown
}

async function request<T = any>(url: string, options: ApiClientOptions = {}): Promise<T> {
  // Add base URL if not already present
  // In production (non-localhost), use same-origin relative paths to avoid CORS
  const isLocalhost = typeof window !== 'undefined' && window.location.hostname === 'localhost'
  const baseUrl = isLocalhost ? 'http://localhost:8000' : ''
  const fullUrl = url.startsWith('http') ? url : `${baseUrl}${url}`
  
  const token = typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null

  const headers: Record<string, string> = {
    'Accept': 'application/json',
    ...(options.headers || {})
  }

  // If a JSON body is provided, set Content-Type and stringify
  let body = options.body
  if (options.json !== undefined) {
    headers['Content-Type'] = 'application/json'
    body = JSON.stringify(options.json)
  }

  if (token && !headers['Authorization']) {
    headers['Authorization'] = `Bearer ${token}`
  }

  const response = await fetch(fullUrl, {
    ...options,
    headers,
    body
  })

  if (!response.ok) {
    const text = await response.text().catch(() => '')
    
    // Only log non-404 errors (404 is expected for resources that don't exist yet)
    // This reduces console noise for expected 404s (videos, analytics, etc.)
    if (response.status !== 404) {
      console.error(`API Error: HTTP ${response.status}: ${text || response.statusText}`)
      console.error(`Request URL: ${fullUrl}`)
      console.error(`Request options:`, options)
    }
    
    // Try to parse JSON error response
    let errorData: any = null
    try {
      if (text) {
        errorData = JSON.parse(text)
      }
    } catch (e) {
      // Not JSON, use text as is
    }
    
    const error: any = new Error(`HTTP ${response.status}: ${text || response.statusText}`)
    error.status = response.status
    error.responseData = errorData || text
    throw error
  }

  const contentType = response.headers.get('content-type') || ''
  if (contentType.includes('application/json')) {
    return response.json() as Promise<T>
  }
  // Allow non-JSON responses for downloads, etc.
  return response as T
}

function get<T = any>(url: string, options?: ApiClientOptions) {
  return request<T>(url, { ...(options || {}), method: 'GET' })
}

function post<T = any>(url: string, json?: unknown, options?: ApiClientOptions) {
  return request<T>(url, { ...(options || {}), method: 'POST', json })
}

function put<T = any>(url: string, json?: unknown, options?: ApiClientOptions) {
  return request<T>(url, { ...(options || {}), method: 'PUT', json })
}

function del<T = any>(url: string, options?: ApiClientOptions) {
  return request<T>(url, { ...(options || {}), method: 'DELETE' })
}

export const apiClient = { request, get, post, put, delete: del }


