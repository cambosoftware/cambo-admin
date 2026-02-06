// Widget components registry
export { default as WidgetWrapper } from './WidgetWrapper.vue'
export { default as WidgetStatsCard } from './WidgetStatsCard.vue'
export { default as WidgetChartLine } from './WidgetChartLine.vue'
export { default as WidgetRecentActivity } from './WidgetRecentActivity.vue'
export { default as WidgetQuickLinks } from './WidgetQuickLinks.vue'
export { default as WidgetWelcome } from './WidgetWelcome.vue'
export { default as WidgetPlaceholder } from './WidgetPlaceholder.vue'

// Widget component map for dynamic rendering
export const widgetComponents = {
    WidgetStatsCard: () => import('./WidgetStatsCard.vue'),
    WidgetChartLine: () => import('./WidgetChartLine.vue'),
    WidgetChartBar: () => import('./WidgetPlaceholder.vue'), // TODO: implement
    WidgetChartPie: () => import('./WidgetPlaceholder.vue'), // TODO: implement
    WidgetRecentActivity: () => import('./WidgetRecentActivity.vue'),
    WidgetQuickLinks: () => import('./WidgetQuickLinks.vue'),
    WidgetDataTable: () => import('./WidgetPlaceholder.vue'), // TODO: implement
    WidgetWelcome: () => import('./WidgetWelcome.vue'),
    WidgetCalendar: () => import('./WidgetPlaceholder.vue'), // TODO: implement
    WidgetNotes: () => import('./WidgetPlaceholder.vue'), // TODO: implement
    WidgetPlaceholder: () => import('./WidgetPlaceholder.vue'),
}

// Get component by name
export function getWidgetComponent(name) {
    return widgetComponents[name] || widgetComponents.WidgetPlaceholder
}
