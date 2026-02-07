<script setup>
import { ref, computed, markRaw } from 'vue'
import AdminLayout from '@/Components/Layout/AdminLayout.vue'
import SidebarItem from '@/Components/Layout/SidebarItem.vue'
import SidebarDivider from '@/Components/Layout/SidebarDivider.vue'
import PageHeader from '@/Components/Layout/PageHeader.vue'

// UI Components
import Button from '@/Components/UI/Button.vue'
import ButtonGroup from '@/Components/UI/ButtonGroup.vue'
import IconButton from '@/Components/UI/IconButton.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import AvatarGroup from '@/Components/UI/AvatarGroup.vue'
import Spinner from '@/Components/UI/Spinner.vue'
import Skeleton from '@/Components/UI/Skeleton.vue'
import Tooltip from '@/Components/UI/Tooltip.vue'
import Divider from '@/Components/UI/Divider.vue'
import AppLink from '@/Components/UI/AppLink.vue'
import Icon from '@/Components/UI/Icon.vue'

// Overlay Components
import Modal from '@/Components/Overlays/Modal.vue'
import ConfirmModal from '@/Components/Overlays/ConfirmModal.vue'
import Drawer from '@/Components/Overlays/Drawer.vue'
import Dropdown from '@/Components/Overlays/Dropdown.vue'
import DropdownItem from '@/Components/Overlays/DropdownItem.vue'
import DropdownDivider from '@/Components/Overlays/DropdownDivider.vue'
import Popover from '@/Components/Overlays/Popover.vue'
import ContextMenu from '@/Components/Overlays/ContextMenu.vue'

// Feedback Components
import Alert from '@/Components/Feedback/Alert.vue'
import ProgressBar from '@/Components/Feedback/ProgressBar.vue'
import EmptyState from '@/Components/Feedback/EmptyState.vue'
import ErrorState from '@/Components/Feedback/ErrorState.vue'

// Container Components
import Card from '@/Components/Containers/Card.vue'
import CardGrid from '@/Components/Containers/CardGrid.vue'
import Accordion from '@/Components/Containers/Accordion.vue'
import AccordionItem from '@/Components/Containers/AccordionItem.vue'
import Tabs from '@/Components/Containers/Tabs.vue'
import Tab from '@/Components/Containers/Tab.vue'
import Collapse from '@/Components/Containers/Collapse.vue'
import Panel from '@/Components/Containers/Panel.vue'

// Form Components
import FormGroup from '@/Components/Form/FormGroup.vue'
import Input from '@/Components/Form/Input.vue'
import Textarea from '@/Components/Form/Textarea.vue'
import Select from '@/Components/Form/Select.vue'
import SelectSearch from '@/Components/Form/SelectSearch.vue'
import SelectMultiple from '@/Components/Form/SelectMultiple.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import CheckboxGroup from '@/Components/Form/CheckboxGroup.vue'
import RadioGroup from '@/Components/Form/RadioGroup.vue'
import RadioCards from '@/Components/Form/RadioCards.vue'
import Switch from '@/Components/Form/Switch.vue'
import Toggle from '@/Components/Form/Toggle.vue'

// Advanced Form Components
import PasswordInput from '@/Components/Form/PasswordInput.vue'
import SearchInput from '@/Components/Form/SearchInput.vue'
import NumberInput from '@/Components/Form/NumberInput.vue'
import TagInput from '@/Components/Form/TagInput.vue'
import SliderInput from '@/Components/Form/SliderInput.vue'
import RangeInput from '@/Components/Form/RangeInput.vue'
import RatingInput from '@/Components/Form/RatingInput.vue'
import DatePicker from '@/Components/Form/DatePicker.vue'
import DateRangePicker from '@/Components/Form/DateRangePicker.vue'
import TimePicker from '@/Components/Form/TimePicker.vue'
import DateTimePicker from '@/Components/Form/DateTimePicker.vue'
import ColorPicker from '@/Components/Form/ColorPicker.vue'
import FilePicker from '@/Components/Form/FilePicker.vue'
import ImagePicker from '@/Components/Form/ImagePicker.vue'
import FileDropzone from '@/Components/Form/FileDropzone.vue'
import RichTextEditor from '@/Components/Form/RichTextEditor.vue'
import MarkdownEditor from '@/Components/Form/MarkdownEditor.vue'
import CodeEditor from '@/Components/Form/CodeEditor.vue'
import CurrencyInput from '@/Components/Form/CurrencyInput.vue'
import PhoneInput from '@/Components/Form/PhoneInput.vue'

// Data Components
import Table from '@/Components/Data/Table.vue'
import TableHead from '@/Components/Data/TableHead.vue'
import TableBody from '@/Components/Data/TableBody.vue'
import TableRow from '@/Components/Data/TableRow.vue'
import TableCell from '@/Components/Data/TableCell.vue'
import SortableHeader from '@/Components/Data/SortableHeader.vue'
import Pagination from '@/Components/Data/Pagination.vue'
import List from '@/Components/Data/List.vue'
import ListItem from '@/Components/Data/ListItem.vue'
import DescriptionList from '@/Components/Data/DescriptionList.vue'
import Timeline from '@/Components/Data/Timeline.vue'
import TimelineItem from '@/Components/Data/TimelineItem.vue'

// Formatter Components
import DateCell from '@/Components/Data/Formatters/DateCell.vue'
import CurrencyCell from '@/Components/Data/Formatters/CurrencyCell.vue'
import StatusCell from '@/Components/Data/Formatters/StatusCell.vue'
import BooleanCell from '@/Components/Data/Formatters/BooleanCell.vue'
import ProgressCell from '@/Components/Data/Formatters/ProgressCell.vue'
import UserCell from '@/Components/Data/Formatters/UserCell.vue'

// Navigation Components
import NavLink from '@/Components/Navigation/NavLink.vue'
import NavGroup from '@/Components/Navigation/NavGroup.vue'
import StepWizard from '@/Components/Navigation/StepWizard.vue'
import BackButton from '@/Components/Navigation/BackButton.vue'

// Stats Components
import StatCard from '@/Components/Stats/StatCard.vue'
import StatGrid from '@/Components/Stats/StatGrid.vue'
import MiniChart from '@/Components/Stats/MiniChart.vue'

// Utility Components
import CopyButton from '@/Components/Utilities/CopyButton.vue'
import ClickToCopy from '@/Components/Utilities/ClickToCopy.vue'
import ExternalLink from '@/Components/Utilities/ExternalLink.vue'
import RelativeTime from '@/Components/Utilities/RelativeTime.vue'
import Kbd from '@/Components/Utilities/Kbd.vue'
import Highlight from '@/Components/Utilities/Highlight.vue'
import CountUp from '@/Components/Utilities/CountUp.vue'

// Chart Components
import LineChart from '@/Components/Charts/LineChart.vue'
import AreaChart from '@/Components/Charts/AreaChart.vue'
import BarChart from '@/Components/Charts/BarChart.vue'
import DonutChart from '@/Components/Charts/DonutChart.vue'
import PieChart from '@/Components/Charts/PieChart.vue'

// New Components
import DarkModeToggle from '@/Components/UI/DarkModeToggle.vue'
import ThemeSelector from '@/Components/UI/ThemeSelector.vue'
import CommandPalette from '@/Components/Navigation/CommandPalette.vue'
import NotificationCenter from '@/Components/Navigation/NotificationCenter.vue'
import KanbanBoard from '@/Components/Data/KanbanBoard.vue'
import Calendar from '@/Components/Data/Calendar.vue'
import FileManager from '@/Components/Data/FileManager.vue'

// Import Heroicons
import {
    HomeIcon,
    UsersIcon,
    FolderIcon,
    ShoppingCartIcon,
    ChartBarIcon,
    Cog6ToothIcon,
    CurrencyEuroIcon,
    CubeIcon,
    PlusIcon,
    PencilIcon,
    TrashIcon,
    EyeIcon,
    ArrowDownTrayIcon,
    HeartIcon,
    BellIcon,
    StarIcon,
    InboxIcon,
    DocumentTextIcon,
    PhotoIcon,
    Cog8ToothIcon,
    ChevronDownIcon,
    MagnifyingGlassIcon,
    EnvelopeIcon,
    LockClosedIcon,
    ListBulletIcon,
    Squares2X2Icon,
} from '@heroicons/vue/24/outline'

// Define props from controller
defineProps({
    stats: {
        type: Object,
        default: () => ({
            users: 1234,
            orders: 567,
            revenue: 45678,
            products: 89
        })
    }
})

// Reactive states for demo
const loadingBtn = ref(false)
const showModal = ref(false)
const showConfirmModal = ref(false)
const showDrawer = ref(false)
const confirmLoading = ref(false)
const activeTab = ref(null)
const progress = ref(65)

const simulateLoading = () => {
    loadingBtn.value = true
    setTimeout(() => { loadingBtn.value = false }, 2000)
}

const handleConfirm = () => {
    confirmLoading.value = true
    setTimeout(() => {
        confirmLoading.value = false
        showConfirmModal.value = false
    }, 1500)
}

// Form demo states
const formName = ref('Jean Dupont')
const formEmail = ref('')
const formPassword = ref('')
const formBio = ref('')
const formCountry = ref(null)
const formCity = ref(null)
const formTags = ref(['vue', 'laravel'])
const formAgree = ref(false)
const formChecked = ref(true)
const formCheckboxGroup = ref(['vue', 'laravel'])
const formNotifs = ref(true)
const formSwitchSuccess = ref(true)
const formGender = ref('male')
const formPlan = ref('pro')
const formView = ref('grid')

// Advanced form demo states
const formSearch = ref('')
const formNumber = ref(5)
const formFormTags = ref(['Vue.js', 'Laravel'])
const formSlider = ref(50)
const formRange = ref([20, 80])
const formRating = ref(3)
const formDate = ref(null)
const formDateRange = ref([null, null])
const formTime = ref(null)
const formDateTime = ref(null)
const formColor = ref('#3b82f6')
const formFile = ref(null)
const formImage = ref(null)
const formDropFiles = ref([])
const formRichText = ref('')
const formMarkdown = ref('# Titre\n\nParagraphe en **gras** et _italique_.\n\n- Item 1\n- Item 2')
const formCode = ref('function hello() {\n  console.log("Hello World!");\n  return true;\n}')
const formCurrency = ref(1299.99)
const formPhone = ref('')

// Data table demo states
const tableSort = ref('name')
const tableSortDir = ref('asc')
const tablePage = ref(1)
const tableFilters = ref({})
const tablePerPage = ref(10)

const tableData = [
    { id: 1, name: 'Jean Dupont', email: 'jean@example.com', status: 'active', amount: 1250.00, progress: 75, created_at: '2024-01-15', verified: true },
    { id: 2, name: 'Marie Martin', email: 'marie@example.com', status: 'pending', amount: 890.50, progress: 45, created_at: '2024-02-20', verified: false },
    { id: 3, name: 'Pierre Bernard', email: 'pierre@example.com', status: 'inactive', amount: 2100.00, progress: 100, created_at: '2024-03-10', verified: true },
    { id: 4, name: 'Sophie Petit', email: 'sophie@example.com', status: 'active', amount: 560.75, progress: 30, created_at: '2024-03-25', verified: true },
    { id: 5, name: 'Luc Moreau', email: 'luc@example.com', status: 'active', amount: 3200.00, progress: 90, created_at: '2024-04-05', verified: false },
]

const handleSort = ({ column, direction }) => {
    tableSort.value = column
    tableSortDir.value = direction
}

const handleFilter = ({ column, value }) => {
    if (value === null) {
        delete tableFilters.value[column]
        tableFilters.value = { ...tableFilters.value }
    } else {
        tableFilters.value = { ...tableFilters.value, [column]: value }
    }
}

// Filtered and sorted table data
const sortedTableData = computed(() => {
    let data = [...tableData]

    // Apply filters
    const filters = tableFilters.value
    if (Object.keys(filters).length > 0) {
        data = data.filter(row => {
            return Object.entries(filters).every(([key, value]) => {
                if (value === null || value === '' || value === undefined) return true
                return row[key] === value
            })
        })
    }

    // Apply sorting
    const col = tableSort.value
    const dir = tableSortDir.value

    return data.sort((a, b) => {
        let valA = a[col]
        let valB = b[col]

        // Handle different types
        if (typeof valA === 'string') {
            valA = valA.toLowerCase()
            valB = valB.toLowerCase()
        }

        if (valA < valB) return dir === 'asc' ? -1 : 1
        if (valA > valB) return dir === 'asc' ? 1 : -1
        return 0
    })
})

// Step wizard state
const currentStep = ref('details')
const wizardSteps = [
    { id: 'details', label: 'Informations', description: 'Détails de base' },
    { id: 'pricing', label: 'Tarification', description: 'Prix et options' },
    { id: 'review', label: 'Vérification', description: 'Relecture finale' },
    { id: 'publish', label: 'Publication', description: 'Mise en ligne' },
]

// Timeline data
const timelineEvents = [
    { id: 1, title: 'Commande créée', description: 'La commande #1234 a été créée', date: 'Il y a 2h', variant: 'primary' },
    { id: 2, title: 'Paiement reçu', description: 'Paiement de 125€ confirmé', date: 'Il y a 1h', variant: 'success' },
    { id: 3, title: 'En préparation', description: 'La commande est en cours de préparation', date: 'Il y a 30min', variant: 'warning' },
]

// Mini chart data
const chartData = [12, 19, 15, 25, 22, 30, 28, 35, 40, 38, 45, 50]

// Chart.js data
const chartLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
const lineChartDatasets = [
    { label: 'Revenus', data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 40000, 38000, 45000, 50000], color: 'primary' },
    { label: 'Dépenses', data: [8000, 12000, 10000, 15000, 14000, 18000, 16000, 20000, 22000, 21000, 25000, 28000], color: 'danger' }
]
const barChartDatasets = [
    { label: 'Ventes', data: [65, 59, 80, 81, 56, 55, 40, 65, 75, 82, 90, 95], color: 'primary' },
    { label: 'Objectif', data: [50, 50, 70, 70, 50, 50, 50, 60, 70, 75, 80, 85], color: 'secondary' }
]
const pieLabels = ['Électronique', 'Vêtements', 'Maison', 'Sports', 'Autres']
const pieData = [35, 25, 20, 12, 8]

const countryOptions = [
    { label: 'France', value: 'fr' },
    { label: 'Belgique', value: 'be' },
    { label: 'Suisse', value: 'ch' },
    { label: 'Canada', value: 'ca' },
    { label: 'Luxembourg', value: 'lu' },
    { label: 'Maroc', value: 'ma' },
    { label: 'Tunisie', value: 'tn' },
    { label: 'Sénégal', value: 'sn' },
]

const tagOptions = [
    { label: 'Vue.js', value: 'vue' },
    { label: 'Laravel', value: 'laravel' },
    { label: 'React', value: 'react' },
    { label: 'Tailwind', value: 'tailwind' },
    { label: 'TypeScript', value: 'typescript' },
    { label: 'PHP', value: 'php' },
]

const planOptions = [
    { label: 'Gratuit', value: 'free', description: 'Pour tester' },
    { label: 'Pro', value: 'pro', description: '29€/mois, fonctionnalités avancées', icon: markRaw(StarIcon) },
    { label: 'Enterprise', value: 'enterprise', description: 'Sur mesure, support dédié', icon: markRaw(CubeIcon) },
]

// Menu items for sidebar
const menuItems = [
    { label: 'Dashboard', icon: markRaw(HomeIcon), href: '/' },
    {
        label: 'Utilisateurs',
        icon: markRaw(UsersIcon),
        children: [
            { label: 'Liste', href: '/users' },
            { label: 'Rôles', href: '/roles' },
            { label: 'Permissions', href: '/permissions' },
        ]
    },
    {
        label: 'Catalogue',
        icon: markRaw(FolderIcon),
        children: [
            {
                label: 'Produits',
                href: '/products',
                children: [
                    { label: 'Liste des produits', href: '/products' },
                    { label: 'Ajouter un produit', href: '/products/create' },
                    { label: 'Import / Export', href: '/products/import' },
                ]
            },
            {
                label: 'Catégories',
                href: '/categories',
                children: [
                    { label: 'Toutes les catégories', href: '/categories' },
                    { label: 'Nouvelle catégorie', href: '/categories/create' },
                ]
            },
            { label: 'Marques', href: '/brands', badge: '12' },
            { label: 'Attributs', href: '/attributes' },
        ]
    },
    { label: 'Commandes', icon: markRaw(ShoppingCartIcon), href: '/orders', badge: '5', badgeVariant: 'danger' },
    { label: 'Rapports', icon: markRaw(ChartBarIcon), href: '/reports' },
]

// Avatar group data
const teamMembers = [
    { name: 'Jean Dupont' },
    { name: 'Marie Martin' },
    { name: 'Pierre Bernard' },
    { name: 'Sophie Petit' },
    { name: 'Luc Moreau' },
    { name: 'Anne Duval' },
    { name: 'Thomas Roux' },
]

// ==========================================
// NEW COMPONENTS DEMO DATA
// ==========================================

// Command Palette data
const commandPaletteRef = ref(null)
const commandGroups = [
    {
        name: 'Navigation',
        items: [
            { id: 'home', label: 'Accueil', icon: markRaw(HomeIcon), href: '/' },
            { id: 'users', label: 'Utilisateurs', icon: markRaw(UsersIcon), href: '/users' },
            { id: 'settings', label: 'Paramètres', icon: markRaw(Cog6ToothIcon), href: '/settings' }
        ]
    },
    {
        name: 'Actions rapides',
        items: [
            { id: 'new-user', label: 'Nouvel utilisateur', description: 'Créer un compte', action: () => alert('Créer utilisateur') },
            { id: 'new-order', label: 'Nouvelle commande', description: 'Ajouter une commande', shortcut: '⌘N' }
        ]
    }
]

// Notifications data
const notifications = ref([
    { id: 1, title: 'Nouvel utilisateur', message: 'Jean Dupont a créé un compte', type: 'success', time: 'Il y a 5 min', read: false },
    { id: 2, title: 'Commande reçue', message: 'Commande #1234 de 150€', type: 'info', time: 'Il y a 30 min', read: false },
    { id: 3, title: 'Stock faible', message: 'Produit "Widget X" en rupture', type: 'warning', time: 'Il y a 1h', read: true },
    { id: 4, title: 'Erreur paiement', message: 'Échec transaction #5678', type: 'error', time: 'Il y a 2h', read: true },
])

const notificationPosition = ref('bottom-end')
const notificationPositionOptions = [
    { value: 'bottom-start', label: 'Bas gauche' },
    { value: 'bottom-center', label: 'Bas centre' },
    { value: 'bottom-end', label: 'Bas droite' },
    { value: 'top-start', label: 'Haut gauche' },
    { value: 'top-center', label: 'Haut centre' },
    { value: 'top-end', label: 'Haut droite' },
]

const markNotificationRead = (id) => {
    const notif = notifications.value.find(n => n.id === id)
    if (notif) notif.read = true
}

const markAllNotificationsRead = () => {
    notifications.value.forEach(n => n.read = true)
}

// Kanban Board data
const kanbanColumns = ref([
    {
        id: 'todo',
        title: 'À faire',
        color: '#6366f1',
        items: [
            { id: 1, title: 'Design nouvelle page', description: 'Créer les maquettes pour la page produit', priority: 'high', labels: ['design'] },
            { id: 2, title: 'Corriger bug #123', priority: 'medium', assignees: [{ name: 'Jean' }] }
        ]
    },
    {
        id: 'in-progress',
        title: 'En cours',
        color: '#f59e0b',
        items: [
            { id: 3, title: 'Développer API', description: 'Endpoints REST pour les commandes', priority: 'high', labels: ['backend'], assignees: [{ name: 'Marie' }, { name: 'Pierre' }] }
        ]
    },
    {
        id: 'done',
        title: 'Terminé',
        color: '#10b981',
        items: [
            { id: 4, title: 'Setup projet', priority: 'low' }
        ]
    }
])

const onKanbanCardMove = ({ card, fromColumn, toColumn, toIndex }) => {
    const from = kanbanColumns.value.find(c => c.id === fromColumn)
    const to = kanbanColumns.value.find(c => c.id === toColumn)
    if (from && to) {
        const cardIndex = from.items.findIndex(i => i.id === card.id)
        if (cardIndex > -1) {
            from.items.splice(cardIndex, 1)
            if (toIndex !== null) {
                to.items.splice(toIndex, 0, card)
            } else {
                to.items.push(card)
            }
        }
    }
}

// Calendar data
const calendarEvents = ref([
    { id: 1, title: 'Réunion équipe', date: new Date().toISOString().split('T')[0], color: 'blue' },
    { id: 2, title: 'Formation Vue.js', date: new Date(Date.now() + 2*24*60*60*1000).toISOString().split('T')[0], color: 'green' },
    { id: 3, title: 'Release v2.0', date: new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0], color: 'purple' },
])

// File Manager data
const fileManagerItems = ref([
    { id: 1, name: 'Documents', type: 'folder' },
    { id: 2, name: 'Images', type: 'folder' },
    { id: 3, name: 'rapport-2024.pdf', type: 'file', size: 2048000, modified: '15/02/2024' },
    { id: 4, name: 'photo-equipe.jpg', type: 'file', size: 1024000, mimeType: 'image/jpeg', modified: '10/02/2024' },
    { id: 5, name: 'presentation.pptx', type: 'file', size: 5120000, modified: '05/02/2024' },
])
const fileManagerPath = ref([])
</script>

<template>
    <AdminLayout title="Dashboard" :breadcrumb="[{ label: 'Dashboard' }]" sidebar-position="left">
        <template #sidebar-header>
            CamboAdmin
        </template>

        <template #sidebar-menu>
            <SidebarItem
                v-for="item in menuItems"
                :key="item.label"
                :label="item.label"
                :icon="item.icon"
                :href="item.href"
                :children="item.children"
                :badge="item.badge"
                :badge-variant="item.badgeVariant"
            />
            <SidebarDivider label="Paramètres" />
            <SidebarItem label="Configuration" :icon="Cog6ToothIcon" href="/settings" />
        </template>

        <!-- Page content -->
        <PageHeader
            title="Dashboard"
            subtitle="Vue d'ensemble de votre application et des composants UI"
        >
            <template #actions>
                <Button variant="primary" :icon="PlusIcon">
                    Nouveau
                </Button>
            </template>
        </PageHeader>

        <!-- Stats cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Utilisateurs</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ stats.users.toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <UsersIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100">Commandes</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ stats.orders.toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <ShoppingCartIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-100">Revenus</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ stats.revenue.toLocaleString() }} &euro;</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <CurrencyEuroIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 p-6 shadow-lg">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-rose-100">Produits</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ stats.products.toLocaleString() }}</p>
                        </div>
                        <div class="rounded-lg bg-white/20 p-3">
                            <CubeIcon class="h-8 w-8 text-white" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- UI COMPONENTS SHOWCASE                       -->
        <!-- ============================================ -->

        <!-- BUTTONS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Buttons</h3>
                <p class="text-sm text-gray-500 mt-1">Variantes, tailles, outline, loading et icônes</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <!-- Variants -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Variantes (solid)</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <Button variant="primary">Primary</Button>
                        <Button variant="secondary">Secondary</Button>
                        <Button variant="success">Success</Button>
                        <Button variant="danger">Danger</Button>
                        <Button variant="warning">Warning</Button>
                        <Button variant="info">Info</Button>
                        <Button variant="ghost">Ghost</Button>
                        <Button variant="link">Link</Button>
                    </div>
                </div>

                <!-- Outline -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Variantes (outline)</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <Button variant="primary" outline>Primary</Button>
                        <Button variant="secondary" outline>Secondary</Button>
                        <Button variant="success" outline>Success</Button>
                        <Button variant="danger" outline>Danger</Button>
                        <Button variant="warning" outline>Warning</Button>
                        <Button variant="info" outline>Info</Button>
                    </div>
                </div>

                <!-- Sizes -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Tailles</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <Button size="xs">Extra Small</Button>
                        <Button size="sm">Small</Button>
                        <Button size="md">Medium</Button>
                        <Button size="lg">Large</Button>
                    </div>
                </div>

                <!-- Icons -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Avec icônes</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <Button :icon="PlusIcon">Créer</Button>
                        <Button variant="danger" :icon="TrashIcon">Supprimer</Button>
                        <Button variant="secondary" :icon-right="ArrowDownTrayIcon">Exporter</Button>
                        <Button :icon="PencilIcon" variant="success">Modifier</Button>
                    </div>
                </div>

                <!-- States -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">États</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <Button :loading="loadingBtn" @click="simulateLoading">
                            {{ loadingBtn ? 'Chargement...' : 'Cliquez pour loading' }}
                        </Button>
                        <Button disabled>Désactivé</Button>
                        <Button pill>Pill</Button>
                        <Button pill :icon="HeartIcon" variant="danger">Pill + Icon</Button>
                    </div>
                </div>

                <!-- Button Group -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">ButtonGroup</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <ButtonGroup>
                            <Button variant="secondary">Gauche</Button>
                            <Button variant="secondary">Centre</Button>
                            <Button variant="secondary">Droite</Button>
                        </ButtonGroup>
                        <ButtonGroup>
                            <Button variant="primary" :icon="EyeIcon">Voir</Button>
                            <Button variant="primary" :icon="PencilIcon">Modifier</Button>
                            <Button variant="primary" :icon="TrashIcon">Supprimer</Button>
                        </ButtonGroup>
                    </div>
                </div>

                <!-- Icon Buttons -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">IconButton</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <IconButton :icon="PencilIcon" variant="primary" label="Modifier" />
                        <IconButton :icon="TrashIcon" variant="danger" label="Supprimer" />
                        <IconButton :icon="EyeIcon" variant="ghost" label="Voir" />
                        <IconButton :icon="HeartIcon" variant="danger" pill label="Favori" />
                        <IconButton :icon="BellIcon" variant="primary" size="lg" label="Notifications" />
                        <IconButton :icon="StarIcon" loading label="Loading" />
                    </div>
                </div>

                <!-- Icon Component -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Icon (wrapper Heroicons)</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Tailles:</span>
                            <Icon :icon="HomeIcon" size="xs" />
                            <Icon :icon="HomeIcon" size="sm" />
                            <Icon :icon="HomeIcon" size="md" />
                            <Icon :icon="HomeIcon" size="lg" />
                            <Icon :icon="HomeIcon" size="xl" />
                            <Icon :icon="HomeIcon" size="2xl" />
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Couleurs:</span>
                            <Icon :icon="HeartIcon" color="primary" />
                            <Icon :icon="HeartIcon" color="success" />
                            <Icon :icon="HeartIcon" color="warning" />
                            <Icon :icon="HeartIcon" color="danger" />
                            <Icon :icon="HeartIcon" color="info" />
                            <Icon :icon="HeartIcon" color="muted" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BADGES -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Badges</h3>
                <p class="text-sm text-gray-500 mt-1">Variantes, tailles, dot, outline et removable</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <!-- Variants -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Variantes (solid)</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge variant="primary">Primary</Badge>
                        <Badge variant="secondary">Secondary</Badge>
                        <Badge variant="success">Success</Badge>
                        <Badge variant="danger">Danger</Badge>
                        <Badge variant="warning">Warning</Badge>
                        <Badge variant="info">Info</Badge>
                    </div>
                </div>

                <!-- Outline -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Outline</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge variant="primary" outline>Primary</Badge>
                        <Badge variant="success" outline>Success</Badge>
                        <Badge variant="danger" outline>Danger</Badge>
                    </div>
                </div>

                <!-- Sizes -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Tailles</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge size="sm" variant="primary">Small</Badge>
                        <Badge size="md" variant="primary">Medium</Badge>
                        <Badge size="lg" variant="primary">Large</Badge>
                    </div>
                </div>

                <!-- Dot -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Avec dot</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge variant="success" dot>En ligne</Badge>
                        <Badge variant="danger" dot>Hors ligne</Badge>
                        <Badge variant="warning" dot>Absent</Badge>
                    </div>
                </div>

                <!-- Pill & Removable -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Pill & Removable</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge variant="primary" pill>Pill</Badge>
                        <Badge variant="success" removable>Removable</Badge>
                        <Badge variant="info" pill removable>Pill + Remove</Badge>
                    </div>
                </div>
            </div>
        </div>

        <!-- AVATARS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Avatars</h3>
                <p class="text-sm text-gray-500 mt-1">Tailles, initiales, statut et groupes</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <!-- Sizes -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Tailles</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <Avatar name="Jean Dupont" size="xs" />
                        <Avatar name="Marie Martin" size="sm" />
                        <Avatar name="Pierre Bernard" size="md" />
                        <Avatar name="Sophie Petit" size="lg" />
                        <Avatar name="Luc Moreau" size="xl" />
                        <Avatar name="Anne Duval" size="2xl" />
                    </div>
                </div>

                <!-- With status -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Avec statut</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <Avatar name="Jean Dupont" size="lg" status="online" />
                        <Avatar name="Marie Martin" size="lg" status="offline" />
                        <Avatar name="Pierre Bernard" size="lg" status="busy" />
                        <Avatar name="Sophie Petit" size="lg" status="away" />
                    </div>
                </div>

                <!-- Rounded variants -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Arrondis</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <Avatar name="Jean Dupont" size="lg" rounded="sm" />
                        <Avatar name="Marie Martin" size="lg" rounded="md" />
                        <Avatar name="Pierre Bernard" size="lg" rounded="lg" />
                        <Avatar name="Sophie Petit" size="lg" rounded="full" />
                    </div>
                </div>

                <!-- Avatar Group -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">AvatarGroup</p>
                    <div class="flex flex-wrap items-center gap-6">
                        <AvatarGroup :items="teamMembers" :max="4" size="md" />
                        <AvatarGroup :items="teamMembers" :max="3" size="lg" />
                    </div>
                </div>
            </div>
        </div>

        <!-- SPINNER & SKELETON -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Spinner -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Spinner</h3>
                    <p class="text-sm text-gray-500 mt-1">Indicateurs de chargement</p>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Tailles</p>
                        <div class="flex items-center gap-4">
                            <Spinner size="xs" />
                            <Spinner size="sm" />
                            <Spinner size="md" />
                            <Spinner size="lg" />
                            <Spinner size="xl" />
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Couleurs</p>
                        <div class="flex items-center gap-4">
                            <Spinner color="primary" />
                            <Spinner color="gray" />
                            <Spinner color="success" />
                            <Spinner color="danger" />
                            <span class="inline-flex p-2 bg-gray-800 rounded-lg">
                                <Spinner color="white" />
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skeleton -->
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Skeleton</h3>
                    <p class="text-sm text-gray-500 mt-1">Placeholders de chargement</p>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Texte (multi-lignes)</p>
                        <Skeleton :lines="3" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Variantes</p>
                        <div class="flex items-center gap-4">
                            <Skeleton variant="circular" width="3rem" />
                            <div class="flex-1 space-y-2">
                                <Skeleton variant="text" width="60%" />
                                <Skeleton variant="text" width="40%" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Rectangulaire</p>
                        <Skeleton variant="rounded" height="6rem" />
                    </div>
                </div>
            </div>
        </div>

        <!-- TOOLTIP, DIVIDER & LINK -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Tooltip, Divider & Link</h3>
            </div>
            <div class="px-6 py-5 space-y-6">
                <!-- Tooltips -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Tooltips</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <Tooltip text="Tooltip en haut" position="top">
                            <Button variant="secondary">Top</Button>
                        </Tooltip>
                        <Tooltip text="Tooltip en bas" position="bottom">
                            <Button variant="secondary">Bottom</Button>
                        </Tooltip>
                        <Tooltip text="Tooltip à gauche" position="left">
                            <Button variant="secondary">Left</Button>
                        </Tooltip>
                        <Tooltip text="Tooltip à droite" position="right">
                            <Button variant="secondary">Right</Button>
                        </Tooltip>
                    </div>
                </div>

                <!-- Dividers -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Dividers</p>
                    <Divider />
                    <Divider label="Ou continuer avec" />
                    <Divider dashed />
                </div>

                <!-- Links -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Links</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <AppLink href="/">Lien interne</AppLink>
                        <AppLink href="/" variant="muted">Lien muted</AppLink>
                        <AppLink href="/" variant="primary">Lien primary</AppLink>
                        <AppLink href="https://laravel.com" external>Lien externe</AppLink>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- OVERLAY COMPONENTS SHOWCASE                  -->
        <!-- ============================================ -->

        <!-- MODAL & CONFIRM MODAL -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Modal & ConfirmModal</h3>
                <p class="text-sm text-gray-500 mt-1">Fenêtres modales et confirmation</p>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <Button variant="primary" @click="showModal = true">Ouvrir Modal</Button>
                    <Button variant="danger" @click="showConfirmModal = true">Ouvrir ConfirmModal</Button>
                </div>

                <Modal v-model:show="showModal" title="Exemple de Modal" size="md">
                    <p class="text-sm text-gray-600">Ceci est le contenu de la modal. Elle supporte les tailles sm, md, lg, xl et full.</p>
                    <p class="text-sm text-gray-600 mt-2">Fermer avec Escape, le bouton X, ou clic sur l'overlay.</p>
                    <template #footer>
                        <div class="flex justify-end gap-2">
                            <Button variant="secondary" @click="showModal = false">Annuler</Button>
                            <Button variant="primary" @click="showModal = false">Confirmer</Button>
                        </div>
                    </template>
                </Modal>

                <ConfirmModal
                    v-model:show="showConfirmModal"
                    title="Supprimer cet élément ?"
                    message="Cette action est irréversible. Toutes les données seront perdues."
                    variant="danger"
                    confirm-label="Supprimer"
                    :loading="confirmLoading"
                    @confirm="handleConfirm"
                />
            </div>
        </div>

        <!-- DRAWER -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Drawer</h3>
                <p class="text-sm text-gray-500 mt-1">Panneaux latéraux (left, right, top, bottom)</p>
            </div>
            <div class="px-6 py-5">
                <div class="flex flex-wrap items-center gap-3">
                    <Button variant="secondary" @click="showDrawer = true">Ouvrir Drawer (droite)</Button>
                </div>

                <Drawer v-model:show="showDrawer" title="Exemple de Drawer" position="right" size="md">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">Le drawer supporte 4 positions et 5 tailles.</p>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-700">Formulaire d'exemple</p>
                            <div class="h-8 bg-gray-100 rounded"></div>
                            <div class="h-8 bg-gray-100 rounded"></div>
                            <div class="h-8 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                    <template #footer>
                        <div class="flex justify-end gap-2">
                            <Button variant="secondary" @click="showDrawer = false">Annuler</Button>
                            <Button variant="primary" @click="showDrawer = false">Sauvegarder</Button>
                        </div>
                    </template>
                </Drawer>
            </div>
        </div>

        <!-- DROPDOWN & POPOVER & CONTEXT MENU -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Dropdown, Popover & ContextMenu</h3>
                <p class="text-sm text-gray-500 mt-1">Menus et popovers interactifs</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <div class="flex flex-wrap items-center gap-6">
                    <!-- Dropdown -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Dropdown</p>
                        <Dropdown align="left">
                            <template #trigger>
                                <Button variant="secondary" :icon-right="ChevronDownIcon">Actions</Button>
                            </template>
                            <DropdownItem :icon="EyeIcon">Voir</DropdownItem>
                            <DropdownItem :icon="PencilIcon">Modifier</DropdownItem>
                            <DropdownDivider />
                            <DropdownItem :icon="TrashIcon" variant="danger">Supprimer</DropdownItem>
                        </Dropdown>
                    </div>

                    <!-- Popover -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Popover</p>
                        <Popover position="bottom" width="280px">
                            <template #trigger>
                                <Button variant="secondary">Popover</Button>
                            </template>
                            <div>
                                <p class="font-semibold text-sm text-gray-900 mb-1">Information</p>
                                <p class="text-sm text-gray-600">Ceci est un popover avec du contenu riche et personnalisable.</p>
                            </div>
                        </Popover>
                    </div>
                </div>

                <!-- Context Menu -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">ContextMenu (clic droit)</p>
                    <ContextMenu>
                        <template #target>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-sm text-gray-500">
                                Faire un clic droit ici
                            </div>
                        </template>
                        <DropdownItem :icon="EyeIcon">Voir</DropdownItem>
                        <DropdownItem :icon="PencilIcon">Modifier</DropdownItem>
                        <DropdownItem :icon="ArrowDownTrayIcon">Télécharger</DropdownItem>
                        <DropdownDivider />
                        <DropdownItem :icon="TrashIcon" variant="danger">Supprimer</DropdownItem>
                    </ContextMenu>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- FEEDBACK COMPONENTS SHOWCASE                 -->
        <!-- ============================================ -->

        <!-- ALERTS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Alert</h3>
                <p class="text-sm text-gray-500 mt-1">Notifications contextuelles avec icônes et bordures</p>
            </div>
            <div class="px-6 py-5 space-y-4">
                <Alert variant="info" title="Information">
                    Ceci est une alerte informative avec titre et icône.
                </Alert>
                <Alert variant="success" title="Succès" dismissible>
                    Opération réalisée avec succès. Cette alerte est fermable.
                </Alert>
                <Alert variant="warning" border="left">
                    Attention, cette action peut avoir des conséquences importantes.
                </Alert>
                <Alert variant="danger" title="Erreur" border="left">
                    Une erreur est survenue. Veuillez réessayer.
                </Alert>
            </div>
        </div>

        <!-- PROGRESS BAR -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">ProgressBar</h3>
                <p class="text-sm text-gray-500 mt-1">Barres de progression avec variantes et animations</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Avec label</p>
                    <ProgressBar :value="progress" show-label />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Variantes & tailles</p>
                    <div class="space-y-3">
                        <ProgressBar :value="80" variant="success" size="lg" />
                        <ProgressBar :value="50" variant="warning" size="md" />
                        <ProgressBar :value="30" variant="danger" size="sm" />
                        <ProgressBar :value="60" variant="info" size="xs" />
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Striped & Indeterminate</p>
                    <div class="space-y-3">
                        <ProgressBar :value="70" variant="primary" striped />
                        <ProgressBar indeterminate variant="info" />
                    </div>
                </div>
            </div>
        </div>

        <!-- EMPTY STATE & ERROR STATE -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">EmptyState</h3>
                </div>
                <EmptyState
                    :icon="InboxIcon"
                    title="Aucun message"
                    description="Vous n'avez pas encore reçu de messages."
                >
                    <template #action>
                        <Button variant="primary" :icon="PlusIcon">Créer un message</Button>
                    </template>
                </EmptyState>
            </div>
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">ErrorState</h3>
                </div>
                <ErrorState
                    title="Erreur de chargement"
                    description="Impossible de charger les données. Vérifiez votre connexion."
                />
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CONTAINER COMPONENTS SHOWCASE                -->
        <!-- ============================================ -->

        <!-- CARD & CARDGRID -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Card & CardGrid</h3>
                <p class="text-sm text-gray-500 mt-1">Cartes avec grille responsive</p>
            </div>
            <div class="px-6 py-5">
                <CardGrid :cols="3" gap="md">
                    <Card title="Carte simple" subtitle="Avec sous-titre">
                        <p class="text-sm text-gray-600">Contenu de la carte avec bordure par défaut.</p>
                    </Card>
                    <Card title="Carte hoverable" hoverable>
                        <p class="text-sm text-gray-600">Cette carte a un effet de survol.</p>
                        <template #footer>
                            <Button variant="primary" size="sm">Action</Button>
                        </template>
                    </Card>
                    <Card title="Carte shadow" :bordered="false">
                        <p class="text-sm text-gray-600">Cette carte utilise une ombre au lieu d'une bordure.</p>
                        <template #header-actions>
                            <Badge variant="success">Actif</Badge>
                        </template>
                    </Card>
                </CardGrid>
            </div>
        </div>

        <!-- ACCORDION -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Accordion</h3>
                <p class="text-sm text-gray-500 mt-1">Panneaux pliables (simple et multiple)</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Simple (un seul ouvert)</p>
                    <Accordion>
                        <AccordionItem title="Qu'est-ce que CamboAdmin ?" default-open>
                            CamboAdmin est un package Laravel pour créer rapidement des backoffices avec Vue 3 et Tailwind CSS.
                        </AccordionItem>
                        <AccordionItem title="Comment l'installer ?" subtitle="Via Composer">
                            Utilisez la commande : composer require cambosoftware/cambo-admin
                        </AccordionItem>
                        <AccordionItem title="Est-ce gratuit ?" disabled>
                            Ce panneau est désactivé.
                        </AccordionItem>
                    </Accordion>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Multiple (plusieurs ouverts)</p>
                    <Accordion multiple :bordered="false">
                        <AccordionItem title="Section A" default-open>
                            Contenu de la section A. Plusieurs panneaux peuvent être ouverts simultanément.
                        </AccordionItem>
                        <AccordionItem title="Section B" default-open>
                            Contenu de la section B. Ce mode est activé avec la prop "multiple".
                        </AccordionItem>
                    </Accordion>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Tabs</h3>
                <p class="text-sm text-gray-500 mt-1">Onglets avec 3 variantes : underline, pills, bordered</p>
            </div>
            <div class="px-6 py-5 space-y-8">
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Underline (défaut)</p>
                    <Tabs>
                        <Tab label="Général" :icon="Cog8ToothIcon">
                            <p class="text-sm text-gray-600">Contenu de l'onglet Général avec une icône.</p>
                        </Tab>
                        <Tab label="Documents" :icon="DocumentTextIcon" badge="3">
                            <p class="text-sm text-gray-600">Contenu de l'onglet Documents avec un badge.</p>
                        </Tab>
                        <Tab label="Photos" :icon="PhotoIcon">
                            <p class="text-sm text-gray-600">Contenu de l'onglet Photos.</p>
                        </Tab>
                    </Tabs>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Pills</p>
                    <Tabs variant="pills">
                        <Tab label="Tout">
                            <p class="text-sm text-gray-600">Affichage de tous les éléments.</p>
                        </Tab>
                        <Tab label="Actifs" badge="12">
                            <p class="text-sm text-gray-600">Affichage des éléments actifs uniquement.</p>
                        </Tab>
                        <Tab label="Archivés">
                            <p class="text-sm text-gray-600">Affichage des éléments archivés.</p>
                        </Tab>
                    </Tabs>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Bordered</p>
                    <Tabs variant="bordered">
                        <Tab label="Profil">
                            <p class="text-sm text-gray-600">Informations du profil utilisateur.</p>
                        </Tab>
                        <Tab label="Sécurité">
                            <p class="text-sm text-gray-600">Paramètres de sécurité du compte.</p>
                        </Tab>
                    </Tabs>
                </div>
            </div>
        </div>

        <!-- COLLAPSE & PANEL -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Collapse</h3>
                    <p class="text-sm text-gray-500 mt-1">Zone de contenu pliable</p>
                </div>
                <div class="px-6 py-5">
                    <Collapse default-open>
                        <template #trigger="{ open, toggle }">
                            <Button variant="secondary" @click="toggle" :icon-right="ChevronDownIcon">
                                {{ open ? 'Masquer' : 'Afficher' }} le contenu
                            </Button>
                        </template>
                        <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">Ce contenu peut être affiché ou masqué avec une transition animée.</p>
                        </div>
                    </Collapse>
                </div>
            </div>
            <div class="bg-white shadow-lg rounded-xl">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Panel</h3>
                    <p class="text-sm text-gray-500 mt-1">Panneau avec header et contenu pliable</p>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <Panel title="Panneau statique" subtitle="Non pliable">
                        <p class="text-sm text-gray-600">Ce panneau a un header fixe.</p>
                    </Panel>
                    <Panel title="Panneau pliable" collapsible :default-open="true">
                        <p class="text-sm text-gray-600">Ce panneau peut être replié en cliquant sur le header.</p>
                        <template #footer>
                            <p class="text-xs text-gray-500">Footer du panneau</p>
                        </template>
                    </Panel>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- FORM COMPONENTS SHOWCASE                     -->
        <!-- ============================================ -->

        <!-- INPUT & TEXTAREA -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Input & Textarea</h3>
                <p class="text-sm text-gray-500 mt-1">Champs texte avec icônes, prefix/suffix, tailles et états</p>
            </div>
            <div class="px-6 py-5 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <FormGroup label="Nom complet" required>
                        <Input v-model="formName" placeholder="Jean Dupont" :icon="UsersIcon" clearable />
                    </FormGroup>
                    <FormGroup label="Email" required>
                        <Input v-model="formEmail" type="email" placeholder="jean@example.com" :icon="EnvelopeIcon" />
                    </FormGroup>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <FormGroup label="Small" hint="Taille sm">
                        <Input size="sm" placeholder="Small input" />
                    </FormGroup>
                    <FormGroup label="Medium" hint="Taille md (défaut)">
                        <Input size="md" placeholder="Medium input" />
                    </FormGroup>
                    <FormGroup label="Large" hint="Taille lg">
                        <Input size="lg" placeholder="Large input" />
                    </FormGroup>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <FormGroup label="Avec prefix/suffix">
                        <Input placeholder="0.00" prefix="EUR" suffix=".00" />
                    </FormGroup>
                    <FormGroup label="Mot de passe" error="Le mot de passe est requis">
                        <Input v-model="formPassword" type="password" placeholder="••••••••" :icon="LockClosedIcon" error />
                    </FormGroup>
                </div>
                <FormGroup label="Biographie" hint="Maximum 200 caractères">
                    <Textarea v-model="formBio" placeholder="Parlez-nous de vous..." :rows="3" :maxlength="200" show-count />
                </FormGroup>
            </div>
        </div>

        <!-- SELECT -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Select, SelectSearch & SelectMultiple</h3>
                <p class="text-sm text-gray-500 mt-1">Listes déroulantes avec recherche et sélection multiple</p>
            </div>
            <div class="px-6 py-5 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <FormGroup label="Select simple">
                        <Select v-model="formCountry" :options="countryOptions" placeholder="Choisir un pays" />
                    </FormGroup>
                    <FormGroup label="Select avec recherche">
                        <SelectSearch v-model="formCity" :options="countryOptions" placeholder="Rechercher un pays..." clearable />
                    </FormGroup>
                    <FormGroup label="Sélection multiple">
                        <SelectMultiple v-model="formTags" :options="tagOptions" placeholder="Ajouter des tags..." />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- CHECKBOX, RADIO, SWITCH, TOGGLE -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Checkbox, Radio, Switch & Toggle</h3>
                <p class="text-sm text-gray-500 mt-1">Contrôles de sélection</p>
            </div>
            <div class="px-6 py-5 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Checkbox -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Checkbox</p>
                        <div class="space-y-2">
                            <Checkbox v-model="formAgree" label="J'accepte les conditions" />
                            <Checkbox v-model="formChecked" label="Option cochée" />
                            <Checkbox :model-value="false" label="Désactivée" disabled />
                        </div>
                    </div>

                    <!-- CheckboxGroup -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">CheckboxGroup (vertical)</p>
                        <CheckboxGroup
                            v-model="formCheckboxGroup"
                            :options="[
                                { label: 'Vue.js', value: 'vue' },
                                { label: 'Laravel', value: 'laravel' },
                                { label: 'React', value: 'react' },
                            ]"
                        />
                    </div>

                    <!-- RadioGroup -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">RadioGroup (vertical)</p>
                        <RadioGroup
                            v-model="formGender"
                            :options="[
                                { label: 'Homme', value: 'male' },
                                { label: 'Femme', value: 'female' },
                                { label: 'Autre', value: 'other' },
                            ]"
                        />
                    </div>

                    <!-- Switch -->
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">Switch</p>
                        <div class="flex flex-col gap-3">
                            <Switch v-model="formNotifs" label="Notifications" />
                            <Switch v-model="formSwitchSuccess" label="Succès" active-color="success" />
                            <Switch :model-value="false" label="Désactivé" disabled />
                        </div>
                    </div>
                </div>

                <!-- Inline variants -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">CheckboxGroup (inline)</p>
                        <CheckboxGroup
                            v-model="formCheckboxGroup"
                            :options="[
                                { label: 'Vue.js', value: 'vue' },
                                { label: 'Laravel', value: 'laravel' },
                                { label: 'React', value: 'react' },
                            ]"
                            inline
                        />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">RadioGroup (inline)</p>
                        <RadioGroup
                            v-model="formGender"
                            :options="[
                                { label: 'Homme', value: 'male' },
                                { label: 'Femme', value: 'female' },
                                { label: 'Autre', value: 'other' },
                            ]"
                            inline
                        />
                    </div>
                </div>

                <!-- Toggle -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">Toggle</p>
                    <div class="flex flex-wrap gap-4">
                        <Toggle
                            v-model="formView"
                            :options="[
                                { label: 'Liste', value: 'list', icon: markRaw(ListBulletIcon) },
                                { label: 'Grille', value: 'grid', icon: markRaw(Squares2X2Icon) },
                            ]"
                        />
                        <Toggle
                            :model-value="'monthly'"
                            :options="[
                                { label: 'Mensuel', value: 'monthly' },
                                { label: 'Annuel', value: 'yearly' },
                            ]"
                            size="lg"
                        />
                    </div>
                </div>

                <!-- RadioCards -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-3">RadioCards</p>
                    <RadioCards
                        v-model="formPlan"
                        :options="planOptions"
                        :cols="3"
                    />
                </div>
            </div>
        </div>

        <!-- PASSWORD, SEARCH, NUMBER INPUTS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">PasswordInput, SearchInput & NumberInput</h3>
                <p class="text-sm text-gray-500 mt-1">Inputs spécialisés avec fonctionnalités avancées</p>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <FormGroup label="Mot de passe">
                        <PasswordInput v-model="formPassword" placeholder="Entrez votre mot de passe" :icon="markRaw(LockClosedIcon)" strength-meter />
                    </FormGroup>
                    <FormGroup label="Recherche">
                        <SearchInput v-model="formSearch" placeholder="Rechercher..." :debounce="300" />
                    </FormGroup>
                    <FormGroup label="Quantité">
                        <NumberInput v-model="formNumber" :min="0" :max="100" :step="1" />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- TAG, SLIDER, RANGE, RATING -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">TagInput, SliderInput, RangeInput & RatingInput</h3>
                <p class="text-sm text-gray-500 mt-1">Inputs interactifs et visuels</p>
            </div>
            <div class="px-6 py-5 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <FormGroup label="Tags">
                        <TagInput v-model="formFormTags" :suggestions="['React', 'Angular', 'Svelte', 'Nuxt', 'Next.js']" placeholder="Ajouter un tag..." />
                    </FormGroup>
                    <FormGroup label="Note">
                        <RatingInput v-model="formRating" :max="5" show-value allow-half clearable />
                    </FormGroup>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <FormGroup label="Slider">
                        <SliderInput v-model="formSlider" :min="0" :max="100" show-value show-min-max />
                    </FormGroup>
                    <FormGroup label="Range">
                        <RangeInput v-model="formRange" :min="0" :max="100" show-values show-min-max />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- DATE, TIME PICKERS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">DatePicker, TimePicker, DateTimePicker & DateRangePicker</h3>
                <p class="text-sm text-gray-500 mt-1">Sélecteurs de date et heure</p>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <FormGroup label="Date">
                        <DatePicker v-model="formDate" />
                    </FormGroup>
                    <FormGroup label="Heure">
                        <TimePicker v-model="formTime" />
                    </FormGroup>
                    <FormGroup label="Date et heure">
                        <DateTimePicker v-model="formDateTime" />
                    </FormGroup>
                    <FormGroup label="Période">
                        <DateRangePicker v-model="formDateRange" />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- COLOR PICKER, CURRENCY, PHONE -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">ColorPicker, CurrencyInput & PhoneInput</h3>
                <p class="text-sm text-gray-500 mt-1">Inputs spécialisés avec formatage</p>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <FormGroup label="Couleur">
                        <ColorPicker v-model="formColor" />
                    </FormGroup>
                    <FormGroup label="Montant">
                        <CurrencyInput v-model="formCurrency" currency="EUR" />
                    </FormGroup>
                    <FormGroup label="Téléphone">
                        <PhoneInput v-model="formPhone" default-country="FR" />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- FILE UPLOAD -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">FilePicker, ImagePicker & FileDropzone</h3>
                <p class="text-sm text-gray-500 mt-1">Upload de fichiers et images</p>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <FormGroup label="Fichier">
                        <FilePicker v-model="formFile" accept=".pdf,.doc,.docx" />
                    </FormGroup>
                    <FormGroup label="Image">
                        <ImagePicker v-model="formImage" />
                    </FormGroup>
                    <FormGroup label="Drag & Drop">
                        <FileDropzone v-model="formDropFiles" accept="image/*,.pdf" :max-size="10" description="Images ou PDF, max 10 Mo" />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- EDITORS -->
        <div class="bg-white shadow-lg rounded-xl mb-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">RichTextEditor, MarkdownEditor & CodeEditor</h3>
                <p class="text-sm text-gray-500 mt-1">Éditeurs de contenu avancés</p>
            </div>
            <div class="px-6 py-5 space-y-5">
                <FormGroup label="Éditeur de texte riche">
                    <RichTextEditor v-model="formRichText" placeholder="Écrivez votre contenu ici..." min-height="120px" />
                </FormGroup>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <FormGroup label="Éditeur Markdown">
                        <MarkdownEditor v-model="formMarkdown" :rows="6" />
                    </FormGroup>
                    <FormGroup label="Éditeur de code">
                        <CodeEditor v-model="formCode" language="javascript" :rows="6" />
                    </FormGroup>
                </div>
            </div>
        </div>

        <!-- Stats Components Section -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Stats Components</h2>

            <div class="space-y-8">
                <!-- StatGrid with StatCards -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">StatGrid + StatCard</h3>
                    <StatGrid :columns="4">
                        <StatCard
                            title="Utilisateurs"
                            :value="12847"
                            :previous-value="11200"
                            format="number"
                            :icon="UsersIcon"
                            icon-color="primary"
                        >
                            <template #chart>
                                <MiniChart :data="chartData" type="area" color="primary" :height="40" />
                            </template>
                        </StatCard>
                        <StatCard
                            title="Revenus"
                            :value="45678"
                            :change="12.5"
                            format="currency"
                            :icon="CurrencyEuroIcon"
                            icon-color="success"
                        >
                            <template #chart>
                                <MiniChart :data="chartData" type="line" color="success" :height="40" />
                            </template>
                        </StatCard>
                        <StatCard
                            title="Commandes"
                            :value="1234"
                            :change="-5.2"
                            format="number"
                            :icon="ShoppingCartIcon"
                            icon-color="warning"
                        >
                            <template #chart>
                                <MiniChart :data="chartData" type="bar" color="warning" :height="40" />
                            </template>
                        </StatCard>
                        <StatCard
                            title="Taux de conversion"
                            :value="3.24"
                            :previous-value="2.8"
                            suffix="%"
                            format="custom"
                            :icon="ChartBarIcon"
                            icon-color="info"
                        />
                    </StatGrid>
                </div>

                <!-- MiniChart variants -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">MiniChart Types</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <Card title="Line Chart">
                            <MiniChart :data="chartData" type="line" color="primary" :height="60" show-dots />
                        </Card>
                        <Card title="Area Chart">
                            <MiniChart :data="chartData" type="area" color="success" :height="60" />
                        </Card>
                        <Card title="Bar Chart">
                            <MiniChart :data="chartData" type="bar" color="warning" :height="60" />
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Display Section -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Data Display Components</h2>

            <div class="space-y-8">
                <!-- Table with SortableHeader -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Table + Tri + Filtres intégrés</h3>
                    <Card :padding="false">
                        <Table hoverable>
                            <TableHead>
                                <tr>
                                    <SortableHeader column="name" :current-sort="tableSort" :current-direction="tableSortDir" @sort="handleSort">Nom</SortableHeader>
                                    <SortableHeader column="email" :current-sort="tableSort" :current-direction="tableSortDir" @sort="handleSort">Email</SortableHeader>
                                    <SortableHeader
                                        column="status"
                                        :current-sort="tableSort"
                                        :current-direction="tableSortDir"
                                        @sort="handleSort"
                                        filterable
                                        :filter-options="[
                                            { label: 'Actif', value: 'active' },
                                            { label: 'En attente', value: 'pending' },
                                            { label: 'Inactif', value: 'inactive' }
                                        ]"
                                        :filter-value="tableFilters.status"
                                        @filter="handleFilter"
                                    >Statut</SortableHeader>
                                    <SortableHeader column="amount" :current-sort="tableSort" :current-direction="tableSortDir" @sort="handleSort" align="right">Montant</SortableHeader>
                                    <SortableHeader column="progress" :current-sort="tableSort" :current-direction="tableSortDir" @sort="handleSort">Progression</SortableHeader>
                                    <SortableHeader
                                        column="verified"
                                        :current-sort="tableSort"
                                        :current-direction="tableSortDir"
                                        @sort="handleSort"
                                        filterable
                                        :filter-options="[
                                            { label: 'Oui', value: true },
                                            { label: 'Non', value: false }
                                        ]"
                                        :filter-value="tableFilters.verified"
                                        @filter="handleFilter"
                                    >Vérifié</SortableHeader>
                                    <SortableHeader column="created_at" :current-sort="tableSort" :current-direction="tableSortDir" @sort="handleSort">Date</SortableHeader>
                                </tr>
                            </TableHead>
                            <TableBody striped>
                                <TableRow v-for="row in sortedTableData" :key="row.id">
                                    <TableCell>
                                        <UserCell :value="{ name: row.name, email: row.email }" :show-email="false" />
                                    </TableCell>
                                    <TableCell>{{ row.email }}</TableCell>
                                    <TableCell><StatusCell :value="row.status" /></TableCell>
                                    <TableCell align="right"><CurrencyCell :value="row.amount" /></TableCell>
                                    <TableCell><ProgressCell :value="row.progress" /></TableCell>
                                    <TableCell><BooleanCell :value="row.verified" /></TableCell>
                                    <TableCell><DateCell :value="row.created_at" format="medium" /></TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </Card>
                </div>

                <!-- Pagination -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Pagination</h3>
                    <div class="space-y-4">
                        <Card title="Standard">
                            <Pagination v-model:current-page="tablePage" :total-pages="10" :total-items="100" :per-page="tablePerPage" />
                        </Card>
                        <Card title="Simple">
                            <Pagination v-model:current-page="tablePage" :total-pages="10" simple />
                        </Card>
                    </div>
                </div>

                <!-- List -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">List</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <Card title="List simple">
                            <List bordered>
                                <ListItem v-for="item in tableData.slice(0, 3)" :key="item.id" hoverable>
                                    <template #leading>
                                        <Avatar :name="item.name" size="sm" />
                                    </template>
                                    <div class="font-medium">{{ item.name }}</div>
                                    <div class="text-sm text-gray-500">{{ item.email }}</div>
                                    <template #trailing>
                                        <Badge :variant="item.status === 'active' ? 'success' : 'secondary'">{{ item.status }}</Badge>
                                    </template>
                                </ListItem>
                            </List>
                        </Card>
                        <Card title="DescriptionList">
                            <DescriptionList
                                :items="[
                                    { label: 'Nom', value: 'Jean Dupont' },
                                    { label: 'Email', value: 'jean@example.com' },
                                    { label: 'Statut', value: 'Actif' },
                                    { label: 'Date', value: '15 janvier 2024' },
                                ]"
                                :columns="2"
                                striped
                            />
                        </Card>
                    </div>
                </div>

                <!-- Timeline -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Timeline</h3>
                    <Card>
                        <Timeline :items="timelineEvents" />
                    </Card>
                </div>
            </div>
        </div>

        <!-- Navigation Components Section -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Navigation Components</h2>

            <div class="space-y-8">
                <!-- StepWizard -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">StepWizard</h3>
                    <div class="space-y-6">
                        <Card title="Horizontal">
                            <StepWizard :steps="wizardSteps" v-model:current-step="currentStep" clickable />
                        </Card>
                        <Card title="Simple (dots)">
                            <StepWizard :steps="wizardSteps" v-model:current-step="currentStep" variant="simple" clickable />
                        </Card>
                    </div>
                </div>

                <!-- NavLink & BackButton -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">NavLink & BackButton</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <Card title="NavLink variants">
                            <div class="space-y-4">
                                <NavGroup label="Default style">
                                    <NavLink href="/" :active="true">Dashboard</NavLink>
                                    <NavLink href="/users">Utilisateurs</NavLink>
                                    <NavLink href="/settings">Paramètres</NavLink>
                                </NavGroup>
                                <NavGroup label="Pills style">
                                    <NavLink href="/" variant="pills" :active="true">Dashboard</NavLink>
                                    <NavLink href="/users" variant="pills">Utilisateurs</NavLink>
                                    <NavLink href="/settings" variant="pills">Paramètres</NavLink>
                                </NavGroup>
                                <NavGroup label="Underline style">
                                    <NavLink href="/" variant="underline" :active="true">Dashboard</NavLink>
                                    <NavLink href="/users" variant="underline">Utilisateurs</NavLink>
                                    <NavLink href="/settings" variant="underline">Paramètres</NavLink>
                                </NavGroup>
                            </div>
                        </Card>
                        <Card title="BackButton">
                            <div class="flex flex-wrap gap-4">
                                <BackButton label="Retour" />
                                <BackButton variant="button" label="Retour" />
                                <BackButton variant="minimal" />
                            </div>
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utilities Section -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Utility Components</h2>

            <div class="grid grid-cols-2 gap-6">
                <Card title="CopyButton & ClickToCopy">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">API Key:</span>
                            <code class="px-2 py-1 bg-gray-100 rounded text-sm">sk_live_abc123xyz789</code>
                            <CopyButton value="sk_live_abc123xyz789" />
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm">ID:</span>
                            <ClickToCopy value="usr_1234567890" />
                        </div>
                    </div>
                </Card>

                <Card title="ExternalLink">
                    <div class="space-y-2">
                        <div><ExternalLink href="https://vuejs.org">Documentation Vue.js</ExternalLink></div>
                        <div><ExternalLink href="https://laravel.com" variant="muted">Laravel.com</ExternalLink></div>
                    </div>
                </Card>

                <Card title="RelativeTime">
                    <div class="space-y-2">
                        <div>Créé: <RelativeTime :date="new Date()" /></div>
                        <div>Modifié: <RelativeTime :date="new Date(Date.now() - 3600000)" /></div>
                        <div>Publié: <RelativeTime :date="new Date(Date.now() - 86400000 * 3)" /></div>
                    </div>
                </Card>

                <Card title="Kbd (Keyboard shortcuts)">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">Sauvegarder: <Kbd :keys="['Ctrl', 'S']" /></div>
                        <div class="flex items-center gap-2">Copier: <Kbd :keys="['Ctrl', 'C']" /></div>
                        <div class="flex items-center gap-2">Rechercher: <Kbd :keys="['Ctrl', 'K']" /></div>
                    </div>
                </Card>

                <Card title="Highlight (search)">
                    <div class="space-y-2">
                        <Highlight text="Vue.js est un framework JavaScript progressif" query="vue" />
                        <Highlight text="Laravel est un framework PHP élégant" query="framework" />
                    </div>
                </Card>

                <Card title="CountUp (animated number)">
                    <div class="text-3xl font-bold">
                        <CountUp :value="12847" :duration="2000" separator=" " /> utilisateurs
                    </div>
                </Card>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CHARTS COMPONENTS SHOWCASE                    -->
        <!-- ============================================ -->

        <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Charts Components (Chart.js)</h2>

            <div class="space-y-8">
                <!-- Line Chart -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">LineChart</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <Card title="Revenus vs Dépenses">
                            <LineChart :labels="chartLabels" :datasets="lineChartDatasets" :height="300" />
                        </Card>
                        <Card title="Revenus (courbe lissée avec remplissage)">
                            <LineChart
                                :labels="chartLabels"
                                :datasets="[lineChartDatasets[0]]"
                                :height="300"
                                :tension="0.4"
                                fill
                                :show-legend="false"
                            />
                        </Card>
                    </div>
                </div>

                <!-- Area Chart -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">AreaChart</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <Card title="Performance mensuelle">
                            <AreaChart
                                :labels="chartLabels"
                                :datasets="lineChartDatasets"
                                :height="300"
                            />
                        </Card>
                        <Card title="Empilé (stacked)">
                            <AreaChart
                                :labels="chartLabels"
                                :datasets="lineChartDatasets"
                                :height="300"
                                stacked
                            />
                        </Card>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">BarChart</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <Card title="Ventes vs Objectif">
                            <BarChart :labels="chartLabels" :datasets="barChartDatasets" :height="300" />
                        </Card>
                        <Card title="Barres horizontales empilées">
                            <BarChart
                                :labels="['T1', 'T2', 'T3', 'T4']"
                                :datasets="[
                                    { label: 'Produits', data: [120, 150, 180, 200], color: 'primary' },
                                    { label: 'Services', data: [80, 100, 90, 110], color: 'success' },
                                    { label: 'Abonnements', data: [40, 50, 60, 70], color: 'warning' }
                                ]"
                                :height="300"
                                horizontal
                                stacked
                            />
                        </Card>
                    </div>
                </div>

                <!-- Pie & Donut Charts -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">PieChart & DonutChart</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Card title="Répartition des ventes (Pie)">
                            <PieChart :labels="pieLabels" :data="pieData" :height="250" />
                        </Card>
                        <Card title="Répartition des ventes (Donut)">
                            <DonutChart :labels="pieLabels" :data="pieData" :height="250" />
                        </Card>
                        <Card title="Donut avec total central">
                            <DonutChart
                                :labels="pieLabels"
                                :data="pieData"
                                :height="250"
                                show-total
                                total-label="Ventes"
                                cutout="70%"
                            />
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent activity (kept from original) -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 dark:from-gray-800 to-white dark:to-gray-800 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activité récente</h3>
            </div>
            <div class="px-6 py-5">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li v-for="(event, index) in [
                            { user: 'Jean Dupont', action: 'a créé une commande', time: 'Il y a 5 min', color: 'bg-blue-500' },
                            { user: 'Marie Martin', action: 's\'est inscrit', time: 'Il y a 15 min', color: 'bg-emerald-500' },
                            { user: 'Pierre Bernard', action: 'a modifié un produit', time: 'Il y a 1h', color: 'bg-purple-500' },
                            { user: 'Sophie Petit', action: 'a annulé une commande', time: 'Il y a 2h', color: 'bg-rose-500' },
                        ]" :key="index" class="relative pb-8">
                            <span v-if="index !== 3" class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gradient-to-b from-gray-200 dark:from-gray-600 to-transparent" aria-hidden="true"></span>
                            <div class="relative flex space-x-4">
                                <div>
                                    <Avatar :name="event.user" size="md" />
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-2">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ event.user }}</span>
                                            {{ event.action }}
                                        </p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-sm text-gray-400 dark:text-gray-500 font-medium">
                                        {{ event.time }}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- NOUVEAUX COMPOSANTS -->
        <!-- ========================================== -->

        <Divider label="Nouveaux Composants" class="my-8" />

        <!-- Dark Mode & Theme -->
        <Card title="Dark Mode & Thème" subtitle="Composants pour gérer le thème de l'application">
            <div class="flex flex-wrap items-center gap-6">
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Toggle Simple</p>
                    <DarkModeToggle />
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Avec Label</p>
                    <DarkModeToggle show-label />
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Mode Complet</p>
                    <DarkModeToggle mode="full" show-label />
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Sélecteur Dropdown</p>
                    <ThemeSelector />
                </div>
            </div>
        </Card>

        <!-- Command Palette -->
        <Card title="Command Palette (Cmd+K)" subtitle="Palette de commandes accessible via raccourci clavier" class="mt-6">
            <div class="flex items-center gap-4">
                <Button @click="commandPaletteRef?.open()">
                    Ouvrir la palette
                </Button>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Ou appuyez sur <Kbd :keys="['⌘', 'K']" /> (Mac) / <Kbd :keys="['Ctrl', 'K']" /> (Windows)
                </p>
            </div>
            <CommandPalette ref="commandPaletteRef" :groups="commandGroups" />
        </Card>

        <!-- Notification Center -->
        <Card title="Centre de Notifications" subtitle="Dropdown de notifications avec badge" class="mt-6" overflow="visible">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Position :</span>
                    <Select
                        v-model="notificationPosition"
                        :options="notificationPositionOptions"
                        size="sm"
                        class="w-40"
                    />
                </div>
                <div class="flex items-center gap-4">
                    <NotificationCenter
                        :notifications="notifications"
                        :position="notificationPosition"
                        @mark-read="markNotificationRead"
                        @mark-all-read="markAllNotificationsRead"
                        @delete="id => notifications = notifications.filter(n => n.id !== id)"
                        @clear-all="notifications = []"
                    />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ notifications.filter(n => !n.read).length }} notification(s) non lue(s)
                    </p>
                </div>
            </div>
        </Card>

        <!-- Kanban Board -->
        <Card title="Kanban Board" subtitle="Tableau kanban avec drag & drop" class="mt-6" :padding="false">
            <div class="p-4 overflow-x-auto">
                <KanbanBoard
                    :columns="kanbanColumns"
                    allow-add
                    @card-move="onKanbanCardMove"
                    @card-click="({ card }) => alert('Clic sur: ' + card.title)"
                />
            </div>
        </Card>

        <!-- Calendar -->
        <Card title="Calendrier" subtitle="Calendrier mensuel avec événements" class="mt-6" :padding="false">
            <div class="p-4">
                <Calendar
                    :events="calendarEvents"
                    :week-starts-on="1"
                    locale="fr-FR"
                    @event-click="({ event }) => alert('Événement: ' + event.title)"
                />
            </div>
        </Card>

        <!-- File Manager -->
        <Card title="Gestionnaire de Fichiers" subtitle="Navigation de fichiers avec vue grille/liste" class="mt-6" :padding="false">
            <div class="p-4">
                <FileManager
                    :items="fileManagerItems"
                    :path="fileManagerPath"
                    view-mode="grid"
                    @folder-open="folder => alert('Ouvrir: ' + folder.name)"
                    @item-double-click="item => alert('Double-clic: ' + item.name)"
                />
            </div>
        </Card>

    </AdminLayout>
</template>
