<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiIntegrationManager;
use App\Services\OpenAIService;
use App\Services\SoftBankService;
use App\Services\OracleService;
use App\Services\MGXService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExternalApiController extends Controller
{
    private ApiIntegrationManager $integrationManager;
    private OpenAIService $openaiService;
    private SoftBankService $softbankService;
    private OracleService $oracleService;
    private MGXService $mgxService;

    public function __construct(
        ApiIntegrationManager $integrationManager,
        OpenAIService $openaiService,
        SoftBankService $softbankService,
        OracleService $oracleService,
        MGXService $mgxService
    ) {
        $this->integrationManager = $integrationManager;
        $this->openaiService = $openaiService;
        $this->softbankService = $softbankService;
        $this->oracleService = $oracleService;
        $this->mgxService = $mgxService;
    }

    /**
     * Get comprehensive Stargate project data
     */
    public function getStargateData(): JsonResponse
    {
        try {
            $data = $this->integrationManager->getStargateProjectData();
            
            return response()->json([
                'status' => 'success',
                'data' => $data['data'] ?? null,
                'message' => 'Stargate project data retrieved successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve Stargate project data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get AI ecosystem overview
     */
    public function getAIEcosystem(): JsonResponse
    {
        try {
            $data = $this->integrationManager->getAIEcosystemOverview();
            
            return response()->json([
                'status' => 'success',
                'data' => $data['data'] ?? null,
                'message' => 'AI ecosystem overview retrieved successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve AI ecosystem overview',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get market analysis
     */
    public function getMarketAnalysis(): JsonResponse
    {
        try {
            $data = $this->integrationManager->getMarketAnalysis();
            
            return response()->json([
                'status' => 'success',
                'data' => $data['data'] ?? null,
                'message' => 'Market analysis retrieved successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve market analysis',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): JsonResponse
    {
        try {
            $data = $this->integrationManager->getPerformanceMetrics();
            
            return response()->json([
                'status' => 'success',
                'data' => $data['data'] ?? null,
                'message' => 'Performance metrics retrieved successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve performance metrics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get service health status
     */
    public function getServiceHealth(): JsonResponse
    {
        try {
            $data = $this->integrationManager->getServiceHealth();
            
            return response()->json([
                'status' => 'success',
                'data' => $data['services'] ?? null,
                'overall_health' => $data['overall_health'] ?? null,
                'message' => 'Service health status retrieved successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve service health status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test all API connections
     */
    public function testConnections(): JsonResponse
    {
        try {
            $data = $this->integrationManager->testAllConnections();
            
            return response()->json([
                'status' => 'success',
                'data' => $data['results'] ?? null,
                'summary' => $data['summary'] ?? null,
                'message' => 'API connections tested successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to test API connections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate AI insights
     */
    public function generateInsights(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'topic' => 'required|string|max:255'
            ]);

            $data = $this->integrationManager->generateInsights($request->input('topic'));
            
            return response()->json([
                'status' => 'success',
                'data' => $data['insights'] ?? null,
                'topic' => $data['topic'] ?? null,
                'message' => 'AI insights generated successfully'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate AI insights',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * OpenAI specific endpoints
     */
    public function openaiGenerateText(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'prompt' => 'required|string',
                'model' => 'string|in:gpt-4,gpt-3.5-turbo',
                'max_tokens' => 'integer|min:1|max:4000',
                'temperature' => 'numeric|min:0|max:2'
            ]);

            $options = $request->only(['model', 'max_tokens', 'temperature']);
            $data = $this->openaiService->generateText($request->input('prompt'), $options);
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'Text generated successfully' : 'Failed to generate text'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate text',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function openaiGenerateImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'prompt' => 'required|string|max:1000',
                'size' => 'string|in:1024x1024,1024x1792,1792x1024',
                'quality' => 'string|in:standard,hd',
                'style' => 'string|in:vivid,natural'
            ]);

            $options = $request->only(['size', 'quality', 'style']);
            $data = $this->openaiService->generateImage($request->input('prompt'), $options);
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'Image generated successfully' : 'Failed to generate image'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * SoftBank specific endpoints
     */
    public function softbankPortfolio(): JsonResponse
    {
        try {
            $data = $this->softbankService->getInvestmentPortfolio();
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'Portfolio data retrieved successfully' : 'Failed to retrieve portfolio data'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve portfolio data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function softbankStargateFunding(): JsonResponse
    {
        try {
            $data = $this->softbankService->getStargateFunding();
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'Stargate funding data retrieved successfully' : 'Failed to retrieve funding data'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve funding data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Oracle specific endpoints
     */
    public function oracleInfrastructure(): JsonResponse
    {
        try {
            $data = $this->oracleService->getCloudInfrastructure();
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'Infrastructure data retrieved successfully' : 'Failed to retrieve infrastructure data'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve infrastructure data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function oracleAIServices(): JsonResponse
    {
        try {
            $data = $this->oracleService->getAIServices();
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'AI services data retrieved successfully' : 'Failed to retrieve AI services data'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve AI services data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * MGX specific endpoints
     */
    public function mgxPlatformData(): JsonResponse
    {
        try {
            $data = $this->mgxService->getPlatformData();
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'Platform data retrieved successfully' : 'Failed to retrieve platform data'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve platform data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function mgxAICapabilities(): JsonResponse
    {
        try {
            $data = $this->mgxService->getAICapabilities();
            
            return response()->json([
                'status' => $data['success'] ? 'success' : 'error',
                'data' => $data,
                'message' => $data['success'] ? 'AI capabilities data retrieved successfully' : 'Failed to retrieve AI capabilities data'
            ], $data['success'] ? 200 : 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve AI capabilities data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
