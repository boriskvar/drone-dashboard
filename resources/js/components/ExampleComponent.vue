<template>
    <div class="alert alert-primary">
        Привет из Vue-компонента!
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
    margin-top: 1rem;
}
</style>
