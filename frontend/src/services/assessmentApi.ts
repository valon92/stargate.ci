import { apiClient } from './apiClient'

export interface AssessmentPayload {
  current_step?: number
  score?: number
  readiness_title?: string
  assessment: Record<string, any>
  recommendations?: Record<string, any>
  metadata?: Record<string, any>
}

export interface AssessmentResult extends AssessmentPayload {
  id: number
  user_id?: number | null
  session_id?: string | null
  created_at: string
  updated_at: string
}

export interface AssessmentListResponse {
  success: boolean
  data: AssessmentResult[]
  pagination: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export interface AssessmentResponse {
  success: boolean
  data: AssessmentResult
  message?: string
}

const baseUrl = 'http://127.0.0.1:8000/api/v1/assessment/results'

function getSessionId(): string {
  const key = 'assessment_session_id'
  let sid = localStorage.getItem(key)
  if (!sid) {
    sid = crypto.randomUUID()
    localStorage.setItem(key, sid)
  }
  return sid
}

export const assessmentApi = {
  async save(payload: AssessmentPayload): Promise<AssessmentResponse> {
    return apiClient.post<AssessmentResponse>(baseUrl, payload, {
      headers: { 'X-Session-Id': getSessionId() }
    })
  },
  async list(params?: { per_page?: number; page?: number }): Promise<AssessmentListResponse> {
    const qs = new URLSearchParams()
    if (params) {
      Object.entries(params).forEach(([k, v]) => v != null && qs.append(k, String(v)))
    }
    const url = qs.toString() ? `${baseUrl}?${qs.toString()}` : baseUrl
    return apiClient.get<AssessmentListResponse>(url, {
      headers: { 'X-Session-Id': getSessionId() }
    })
  },
  async get(id: number): Promise<AssessmentResponse> {
    return apiClient.get<AssessmentResponse>(`${baseUrl}/${id}`, {
      headers: { 'X-Session-Id': getSessionId() }
    })
  }
}


