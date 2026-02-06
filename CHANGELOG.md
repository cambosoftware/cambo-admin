# Changelog

All notable changes to `cambo-admin` will be documented in this file.

## [1.0.0] - 2026-02-06

### Added

- Initial release of CamboAdmin
- 150+ Vue.js components organized in 14 categories
- 27 Vue pages for admin functionality
- Authentication system with 2FA support
- Role and permission management with middleware
- Real-time notification center
- Activity logging with automatic tracking
- Dashboard builder with drag & drop widgets
- File manager with local and S3 support
- Dynamic settings manager
- Import/Export functionality (CSV, Excel, PDF)
- Multi-language support with RTL
- Theme customization system
- CRUD generator command (`cambo:crud`)
- Page generator command (`cambo:page`)
- Component generator command (`cambo:component`)
- Module installation command (`cambo:add`)
- Interactive installation wizard (`cambo:install`)
- 104 unit and feature tests

### Components

#### Layout (8)
- AdminLayout, Sidebar, SidebarItem, SidebarDivider
- Navbar, Breadcrumb, PageHeader, Container

#### UI (12)
- Button, ButtonGroup, IconButton, Badge
- Avatar, AvatarGroup, Icon, Spinner
- Skeleton, Tooltip, Divider, AppLink

#### Forms (34)
- Form, FormGroup, Input, Textarea, Select
- SelectSearch, SelectMultiple, Checkbox, CheckboxGroup
- Radio, RadioGroup, RadioCards, Switch, Toggle
- DatePicker, DateRangePicker, TimePicker, DateTimePicker
- ColorPicker, FilePicker, ImagePicker, FileDropzone
- RichTextEditor, MarkdownEditor, CodeEditor, TagInput
- SliderInput, RangeInput, RatingInput, PasswordInput
- SearchInput, PhoneInput, CurrencyInput, NumberInput

#### Data Display (38)
- Table, TableHead, TableBody, TableRow, TableCell
- SortableHeader, Pagination, DataTable
- List, ListItem, DescriptionList, Tree, Timeline
- 10 DataTable sub-components
- 12 cell formatters

#### Charts (9)
- Chart wrapper, LineChart, AreaChart, BarChart
- DonutChart, PieChart, StatCard, StatGrid, MiniChart

#### Overlays (8)
- Modal, ConfirmModal, Drawer, Dropdown
- DropdownItem, DropdownDivider, Popover, ContextMenu

#### Feedback (6)
- Alert, Toast, ToastContainer
- ProgressBar, EmptyState, ErrorState

#### Containers (8)
- Card, CardGrid, Accordion, AccordionItem
- Tabs, Tab, Collapse, Panel

#### Navigation (4)
- NavLink, NavGroup, StepWizard, BackButton

#### Utilities (7)
- CopyButton, ClickToCopy, ExternalLink
- Highlight, RelativeTime, CountUp, Kbd
