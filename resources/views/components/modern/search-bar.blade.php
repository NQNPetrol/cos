<div class="modern-search-bar-container" x-data="searchBarData" x-cloak>
    <!-- Search Bar Input (always visible) -->
    <div class="modern-search-bar" @click="searchModalOpen = true">
        <svg class="modern-search-bar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input 
            type="text" 
            placeholder="Buscar en la aplicación..." 
            x-model="query"
            @input="search()"
            @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
            @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, -1)"
            @keydown.enter.prevent="selectResult(selectedIndex >= 0 ? selectedIndex : 0)"
            @keydown.escape="searchModalOpen = false"
            @focus="searchModalOpen = true"
            readonly
        >
    </div>
    
    <!-- Search Modal -->
    <div x-show="searchModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform translateY(-10px)"
         x-transition:enter-end="opacity-100 transform translateY(0)"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translateY(0)"
         x-transition:leave-end="opacity-0 transform translateY(-10px)"
         @click.away="searchModalOpen = false"
         class="modern-search-modal">
        <!-- Modal Header with Back Button -->
        <div class="modern-search-modal-header">
            <button @click="searchModalOpen = false" class="modern-search-back-btn" type="button">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <input 
                type="text" 
                placeholder="Buscar en la aplicación..." 
                x-model="query"
                @input="search()"
                @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
                @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, -1)"
                @keydown.enter.prevent="selectResult(selectedIndex >= 0 ? selectedIndex : 0)"
                @keydown.escape="searchModalOpen = false"
                autofocus
            >
        </div>
        
        <!-- Search Results -->
        <div class="modern-search-modal-results">
            <template x-if="results.length === 0 && query.length > 0">
                <div class="modern-search-empty">
                    No hay resultados para tu búsqueda
                </div>
            </template>
            <template x-for="(result, index) in results" :key="index">
                <button 
                    @click="selectResult(index)"
                    @mouseenter="selectedIndex = index"
                    :class="{'modern-search-result-active': selectedIndex === index}"
                    class="modern-search-result"
                    type="button">
                    <svg class="modern-search-result-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="result.icon"></svg>
                    <div class="modern-search-result-content">
                        <div class="modern-search-result-title" x-text="result.label"></div>
                        <div class="modern-search-result-category" x-text="result.category"></div>
                    </div>
                </button>
            </template>
        </div>
    </div>
</div>

<script>
// Make searchBarData available globally for Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('searchBarData', () => {
        return {
            query: '',
            results: [],
            selectedIndex: -1,
            open: false,
            searchModalOpen: false,
            searchIndex: [],
            
            init() {
                // Load search index - try multiple times if not ready
                this.loadSearchIndex();
                
                // Retry if search index not loaded
                if (this.searchIndex.length === 0) {
                    const retryInterval = setInterval(() => {
                        this.loadSearchIndex();
                        if (this.searchIndex.length > 0 || typeof window.searchIndex !== 'undefined') {
                            clearInterval(retryInterval);
                        }
                    }, 100);
                    
                    // Stop retrying after 2 seconds
                    setTimeout(() => clearInterval(retryInterval), 2000);
                }
            },
            
            loadSearchIndex() {
                if (typeof window.searchIndex !== 'undefined' && Array.isArray(window.searchIndex)) {
                    this.searchIndex = window.searchIndex;
                }
            },
            
            search() {
                // Reload index if empty
                if (this.searchIndex.length === 0) {
                    this.loadSearchIndex();
                }
                
                if (!this.query || this.query.trim().length < 1) {
                    this.results = [];
                    this.open = false;
                    return;
                }
                
                const query = this.query.toLowerCase().trim();
                this.results = this.searchIndex
                    .filter(item => {
                        const labelMatch = item.label && item.label.toLowerCase().includes(query);
                        const categoryMatch = item.category && item.category.toLowerCase().includes(query);
                        const routeMatch = item.route && item.route.toLowerCase().includes(query);
                        return labelMatch || categoryMatch || routeMatch;
                    })
                    .slice(0, 10);
                
                this.selectedIndex = -1;
                this.open = this.results.length > 0;
                // Keep modal open if it's open
                if (this.searchModalOpen) {
                    this.searchModalOpen = true;
                }
            },
            
            selectResult(index) {
                if (index >= 0 && index < this.results.length) {
                    const result = this.results[index];
                    if (window.modernNavigation) {
                        window.modernNavigation.navigateToRoute(result);
                    } else {
                        window.location.href = result.path;
                    }
                    this.open = false;
                    this.searchModalOpen = false;
                    this.query = '';
                }
            }
        };
    });
});
</script>


