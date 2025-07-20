<template>
    <div class="alert alert-primary">
        ✅ Пример Vue-компонента работает!
    </div>
</template>

<script>
export default {
    name: 'ExampleComponent',
    mounted() {
        console.log('🔧 ExampleComponent подключен и отрендерен');
    }
}
</script>

<style scoped>
.alert {
    font-size: 1.1rem;
}
</style>
