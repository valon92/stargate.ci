import { apiClient } from './apiClient'

export interface Product {
  id: number
  subscriber_id?: number
  name: string
  slug: string
  description: string
  category: 'api' | 'tools' | 'sdk' | 'cloud' | 'documentation' | 'libraries'
  icon: string
  documentation_url?: string
  github_url?: string
  demo_url?: string
  features?: string[]
  is_new?: boolean
  is_popular?: boolean
  downloads_count?: number
  stars_count?: number
  users_count?: number
  status?: 'draft' | 'published' | 'archived'
  created_at?: string
  updated_at?: string
  subscriber?: {
    id: number
    name?: string
    email?: string
    username?: string
  }
}

export interface CreateProductRequest {
  name: string
  description: string
  category: 'api' | 'tools' | 'sdk' | 'cloud' | 'documentation' | 'libraries'
  icon?: string
  documentation_url?: string
  github_url?: string
  demo_url?: string
  features?: string[]
  status?: 'draft' | 'published'
}

export interface UpdateProductRequest extends Partial<CreateProductRequest> {
  status?: 'draft' | 'published' | 'archived'
}

export interface GetProductsParams {
  category?: string
  search?: string
  my_products?: boolean
  per_page?: number
  page?: number
}

class ProductsService {
  /**
   * Get all products
   */
  async getProducts(params: GetProductsParams = {}): Promise<{
    success: boolean
    data: Product[]
    pagination?: {
      current_page: number
      last_page: number
      per_page: number
      total: number
    }
  }> {
    try {
      const queryParams = new URLSearchParams()
      
      if (params.category && params.category !== 'all') {
        queryParams.append('category', params.category)
      }
      
      if (params.search) {
        queryParams.append('search', params.search)
      }
      
      if (params.my_products) {
        queryParams.append('my_products', 'true')
      }
      
      if (params.per_page) {
        queryParams.append('per_page', params.per_page.toString())
      }
      
      if (params.page) {
        queryParams.append('page', params.page.toString())
      }

      const response = await apiClient.get(`/api/v1/products?${queryParams.toString()}`)
      // apiClient.get() already returns the parsed JSON response
      return response
    } catch (error: any) {
      console.error('Error fetching products:', error)
      throw error
    }
  }

  /**
   * Get a single product
   */
  async getProduct(id: number): Promise<{
    success: boolean
    data: Product
  }> {
    try {
      const response = await apiClient.get(`/api/v1/products/${id}`)
      return response.data
    } catch (error: any) {
      console.error('Error fetching product:', error)
      throw error
    }
  }

  /**
   * Create a new product
   */
  async createProduct(data: CreateProductRequest): Promise<{
    success: boolean
    data: Product
    message: string
  }> {
    try {
      const response = await apiClient.post('/api/v1/products', data)
      return response.data
    } catch (error: any) {
      console.error('Error creating product:', error)
      throw error
    }
  }

  /**
   * Update a product
   */
  async updateProduct(id: number, data: UpdateProductRequest): Promise<{
    success: boolean
    data: Product
    message: string
  }> {
    try {
      const response = await apiClient.put(`/api/v1/products/${id}`, data)
      return response.data
    } catch (error: any) {
      console.error('Error updating product:', error)
      throw error
    }
  }

  /**
   * Delete a product
   */
  async deleteProduct(id: number): Promise<{
    success: boolean
    message: string
  }> {
    try {
      const response = await apiClient.delete(`/api/v1/products/${id}`)
      return response.data
    } catch (error: any) {
      console.error('Error deleting product:', error)
      throw error
    }
  }

  /**
   * Get user's products
   */
  async getMyProducts(): Promise<{
    success: boolean
    data: Product[]
  }> {
    try {
      const response = await apiClient.get('/api/v1/products/my/list')
      return response.data
    } catch (error: any) {
      console.error('Error fetching my products:', error)
      throw error
    }
  }
}

export const productsService = new ProductsService()
export default productsService
