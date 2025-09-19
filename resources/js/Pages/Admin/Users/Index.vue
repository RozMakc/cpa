<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  users: { type: Object, required: true }, // Laravel paginator
  roles: { type: Array, default: () => [] },
})

// флеш-сообщения, если есть
const page = usePage()
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Пользователи</h1>

    <div v-if="page.props.flash?.status" class="mb-4 text-green-600">
      {{ page.props.flash.status }}
    </div>

    <div class="overflow-x-auto rounded border">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="text-left p-3">ID</th>
            <th class="text-left p-3">Имя</th>
            <th class="text-left p-3">Email</th>
            <th class="text-left p-3">Телефон</th>
            <th class="text-left p-3">Роли</th>
            <th class="text-left p-3">Статус</th>
            <th class="text-left p-3">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users.data" :key="u.id" class="border-t">
            <td class="p-3">{{ u.id }}</td>
            <td class="p-3">{{ u.name }}</td>
            <td class="p-3">{{ u.email }}</td>
            <td class="p-3">{{ u.phone }}</td>
            <td class="p-3">
              <span v-if="u.roles && u.roles.length">
                {{ u.roles.map(r => r.name).join(', ') }}
              </span>
              <span v-else>—</span>
            </td>
            <td class="p-3">
              <span :class="u.is_active ? 'text-green-600' : 'text-red-600'">
                {{ u.is_active ? 'Активен' : 'Не активен' }}
              </span>
            </td>
            <td class="p-3">
              <!-- используем прямую ссылку, чтобы не зависеть от Ziggy -->
              <Link :href="`/users/${u.id}/edit`" class="underline">Редактировать</Link>
            </td>
          </tr>
          <tr v-if="!users.data || users.data.length === 0">
            <td class="p-3" colspan="7">Пока пусто.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Пагинация -->
    <nav v-if="users.links && users.links.length" class="mt-4 flex gap-2 flex-wrap">
      <Link
        v-for="(l, i) in users.links"
        :key="i"
        :href="l.url || '#'"
        :class="[
          'px-3 py-1 border rounded',
          l.active ? 'bg-gray-800 text-white' : 'bg-white',
          !l.url ? 'opacity-50 pointer-events-none' : ''
        ]"
        v-html="l.label"
      />
    </nav>
  </div>
</template>

<style scoped>
table { border-collapse: collapse; }
th, td { white-space: nowrap; }
</style>
