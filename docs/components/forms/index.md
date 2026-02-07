# Form Components

Complete set of form inputs and controls.

## Basic Inputs

| Component | Description |
|-----------|-------------|
| [Form](./form.md) | Form wrapper with Inertia integration |
| [FormGroup](./form-group.md) | Label, input, and error layout |
| [Input](./input.md) | Text input field |
| [Textarea](./textarea.md) | Multi-line text input |
| [Select](./select.md) | Native select dropdown |
| [SelectSearch](./select-search.md) | Searchable select |
| [SelectMultiple](./select-multiple.md) | Multi-select with tags |
| [Checkbox](./checkbox.md) | Single checkbox |
| [CheckboxGroup](./checkbox-group.md) | Multiple checkboxes |
| [Radio](./radio.md) | Single radio button |
| [RadioGroup](./radio-group.md) | Radio button group |
| [RadioCards](./radio-cards.md) | Card-style radio selection |
| [Switch](./switch.md) | Toggle switch |
| [Toggle](./toggle.md) | Segmented toggle buttons |

## Advanced Inputs

| Component | Description |
|-----------|-------------|
| [DatePicker](./date-picker.md) | Date selection |
| [DateRangePicker](./date-range-picker.md) | Date range selection |
| [TimePicker](./time-picker.md) | Time selection |
| [DateTimePicker](./date-time-picker.md) | Date and time selection |
| [ColorPicker](./color-picker.md) | Color selection |
| [FilePicker](./file-picker.md) | File upload button |
| [ImagePicker](./image-picker.md) | Image upload with preview |
| [FileDropzone](./file-dropzone.md) | Drag and drop file upload |
| [RichTextEditor](./rich-text-editor.md) | WYSIWYG editor |
| [MarkdownEditor](./markdown-editor.md) | Markdown editor |
| [CodeEditor](./code-editor.md) | Code editor with syntax highlighting |
| [TagInput](./tag-input.md) | Tag/chip input |
| [SliderInput](./slider-input.md) | Range slider |
| [RangeInput](./range-input.md) | Min/max range input |
| [RatingInput](./rating-input.md) | Star rating |
| [PasswordInput](./password-input.md) | Password with visibility toggle |
| [SearchInput](./search-input.md) | Search field |
| [PhoneInput](./phone-input.md) | Phone number input |
| [CurrencyInput](./currency-input.md) | Currency input |
| [NumberInput](./number-input.md) | Numeric input with controls |

## Usage

```vue
<template>
  <Form @submit="submit">
    <FormGroup label="Name" :error="form.errors.name">
      <Input v-model="form.name" placeholder="Enter your name" />
    </FormGroup>

    <FormGroup label="Email" :error="form.errors.email">
      <Input v-model="form.email" type="email" />
    </FormGroup>

    <FormGroup label="Role">
      <Select v-model="form.role" :options="roles" />
    </FormGroup>

    <Button type="submit" :loading="form.processing">
      Save
    </Button>
  </Form>
</template>
```
