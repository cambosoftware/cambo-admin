import { defineClientConfig } from 'vuepress/client'
import Playground from './components/Playground.vue'
import LiveDemo from './components/LiveDemo.vue'

// Import demo components for playground
// UI Components
import DemoButton from './components/demos/DemoButton.vue'
import DemoBadge from './components/demos/DemoBadge.vue'
import DemoAvatar from './components/demos/DemoAvatar.vue'
import DemoSpinner from './components/demos/DemoSpinner.vue'
import DemoSkeleton from './components/demos/DemoSkeleton.vue'
import DemoTooltip from './components/demos/DemoTooltip.vue'
import DemoDivider from './components/demos/DemoDivider.vue'

// Form Components - Basic
import DemoForm from './components/demos/DemoForm.vue'
import DemoFormGroup from './components/demos/DemoFormGroup.vue'
import DemoInput from './components/demos/DemoInput.vue'
import DemoTextarea from './components/demos/DemoTextarea.vue'
import DemoCheckbox from './components/demos/DemoCheckbox.vue'
import DemoCheckboxGroup from './components/demos/DemoCheckboxGroup.vue'
import DemoRadio from './components/demos/DemoRadio.vue'
import DemoRadioGroup from './components/demos/DemoRadioGroup.vue'
import DemoRadioCards from './components/demos/DemoRadioCards.vue'
import DemoSwitch from './components/demos/DemoSwitch.vue'
import DemoToggle from './components/demos/DemoToggle.vue'
import DemoSelect from './components/demos/DemoSelect.vue'
import DemoSelectSearch from './components/demos/DemoSelectSearch.vue'
import DemoSelectMultiple from './components/demos/DemoSelectMultiple.vue'

// Form Components - Advanced
import DemoDatePicker from './components/demos/DemoDatePicker.vue'
import DemoDateRangePicker from './components/demos/DemoDateRangePicker.vue'
import DemoTimePicker from './components/demos/DemoTimePicker.vue'
import DemoDateTimePicker from './components/demos/DemoDateTimePicker.vue'
import DemoColorPicker from './components/demos/DemoColorPicker.vue'
import DemoFilePicker from './components/demos/DemoFilePicker.vue'
import DemoImagePicker from './components/demos/DemoImagePicker.vue'
import DemoFileDropzone from './components/demos/DemoFileDropzone.vue'
import DemoRichTextEditor from './components/demos/DemoRichTextEditor.vue'
import DemoMarkdownEditor from './components/demos/DemoMarkdownEditor.vue'
import DemoCodeEditor from './components/demos/DemoCodeEditor.vue'
import DemoTagInput from './components/demos/DemoTagInput.vue'
import DemoSliderInput from './components/demos/DemoSliderInput.vue'
import DemoRangeInput from './components/demos/DemoRangeInput.vue'
import DemoRatingInput from './components/demos/DemoRatingInput.vue'
import DemoPasswordInput from './components/demos/DemoPasswordInput.vue'
import DemoSearchInput from './components/demos/DemoSearchInput.vue'
import DemoPhoneInput from './components/demos/DemoPhoneInput.vue'
import DemoCurrencyInput from './components/demos/DemoCurrencyInput.vue'
import DemoNumberInput from './components/demos/DemoNumberInput.vue'

// Feedback Components
import DemoAlert from './components/demos/DemoAlert.vue'
import DemoToast from './components/demos/DemoToast.vue'
import DemoProgressBar from './components/demos/DemoProgressBar.vue'
import DemoEmptyState from './components/demos/DemoEmptyState.vue'

// Container Components
import DemoCard from './components/demos/DemoCard.vue'
import DemoAccordion from './components/demos/DemoAccordion.vue'
import DemoTabs from './components/demos/DemoTabs.vue'
import DemoCollapse from './components/demos/DemoCollapse.vue'

// Data Components
import DemoTable from './components/demos/DemoTable.vue'
import DemoPagination from './components/demos/DemoPagination.vue'
import DemoTimeline from './components/demos/DemoTimeline.vue'

// Overlay Components
import DemoModal from './components/demos/DemoModal.vue'
import DemoDrawer from './components/demos/DemoDrawer.vue'
import DemoDropdown from './components/demos/DemoDropdown.vue'

// Navigation Components
import DemoNavLink from './components/demos/DemoNavLink.vue'
import DemoBreadcrumb from './components/demos/DemoBreadcrumb.vue'
import DemoStepWizard from './components/demos/DemoStepWizard.vue'

// Charts Components
import DemoStatCard from './components/demos/DemoStatCard.vue'

export default defineClientConfig({
  enhance({ app }) {
    // Register global components
    app.component('Playground', Playground)
    app.component('LiveDemo', LiveDemo)

    // Register UI demo components
    app.component('DemoButton', DemoButton)
    app.component('DemoBadge', DemoBadge)
    app.component('DemoAvatar', DemoAvatar)
    app.component('DemoSpinner', DemoSpinner)
    app.component('DemoSkeleton', DemoSkeleton)
    app.component('DemoTooltip', DemoTooltip)
    app.component('DemoDivider', DemoDivider)

    // Register Form demo components - Basic
    app.component('DemoForm', DemoForm)
    app.component('DemoFormGroup', DemoFormGroup)
    app.component('DemoInput', DemoInput)
    app.component('DemoTextarea', DemoTextarea)
    app.component('DemoCheckbox', DemoCheckbox)
    app.component('DemoCheckboxGroup', DemoCheckboxGroup)
    app.component('DemoRadio', DemoRadio)
    app.component('DemoRadioGroup', DemoRadioGroup)
    app.component('DemoRadioCards', DemoRadioCards)
    app.component('DemoSwitch', DemoSwitch)
    app.component('DemoToggle', DemoToggle)
    app.component('DemoSelect', DemoSelect)
    app.component('DemoSelectSearch', DemoSelectSearch)
    app.component('DemoSelectMultiple', DemoSelectMultiple)

    // Register Form demo components - Advanced
    app.component('DemoDatePicker', DemoDatePicker)
    app.component('DemoDateRangePicker', DemoDateRangePicker)
    app.component('DemoTimePicker', DemoTimePicker)
    app.component('DemoDateTimePicker', DemoDateTimePicker)
    app.component('DemoColorPicker', DemoColorPicker)
    app.component('DemoFilePicker', DemoFilePicker)
    app.component('DemoImagePicker', DemoImagePicker)
    app.component('DemoFileDropzone', DemoFileDropzone)
    app.component('DemoRichTextEditor', DemoRichTextEditor)
    app.component('DemoMarkdownEditor', DemoMarkdownEditor)
    app.component('DemoCodeEditor', DemoCodeEditor)
    app.component('DemoTagInput', DemoTagInput)
    app.component('DemoSliderInput', DemoSliderInput)
    app.component('DemoRangeInput', DemoRangeInput)
    app.component('DemoRatingInput', DemoRatingInput)
    app.component('DemoPasswordInput', DemoPasswordInput)
    app.component('DemoSearchInput', DemoSearchInput)
    app.component('DemoPhoneInput', DemoPhoneInput)
    app.component('DemoCurrencyInput', DemoCurrencyInput)
    app.component('DemoNumberInput', DemoNumberInput)

    // Register Feedback demo components
    app.component('DemoAlert', DemoAlert)
    app.component('DemoToast', DemoToast)
    app.component('DemoProgressBar', DemoProgressBar)
    app.component('DemoEmptyState', DemoEmptyState)

    // Register Container demo components
    app.component('DemoCard', DemoCard)
    app.component('DemoAccordion', DemoAccordion)
    app.component('DemoTabs', DemoTabs)
    app.component('DemoCollapse', DemoCollapse)

    // Register Data demo components
    app.component('DemoTable', DemoTable)
    app.component('DemoPagination', DemoPagination)
    app.component('DemoTimeline', DemoTimeline)

    // Register Overlay demo components
    app.component('DemoModal', DemoModal)
    app.component('DemoDrawer', DemoDrawer)
    app.component('DemoDropdown', DemoDropdown)

    // Register Navigation demo components
    app.component('DemoNavLink', DemoNavLink)
    app.component('DemoBreadcrumb', DemoBreadcrumb)
    app.component('DemoStepWizard', DemoStepWizard)

    // Register Charts demo components
    app.component('DemoStatCard', DemoStatCard)
  }
})
