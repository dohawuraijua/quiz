<template>
    <div class="skeleton-card mb-4">
        <div class="skeleton-image"></div>
        <div class="skeleton-text-line w-75"></div>
    </div>
</template>

<style scoped>
.skeleton-card {
    padding: 1rem;
    border-radius: 0.5rem;
    background: #fff;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.skeleton-image {
    width: 100%;
    height: 150px;
    background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 50%, #e0e0e0 75%);
    background-size: 400% 100%;
    animation: shimmer 1.4s infinite;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.skeleton-text-line {
    height: 16px;
    background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 50%, #e0e0e0 75%);
    background-size: 400% 100%;
    animation: shimmer 1.4s infinite;
    border-radius: 4px;
    margin-bottom: 0.5rem;
}

@keyframes shimmer {
    0% {
        background-position: 100% 0;
    }

    100% {
        background-position: -100% 0;
    }
}
</style>
