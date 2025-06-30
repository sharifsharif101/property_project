document.addEventListener('DOMContentLoaded', () => {
    const toggleViewBtn = document.getElementById('toggleViewBtn');
    const fullTableContainer = document.getElementById('fullTableContainer');
    const groupedView = document.getElementById('groupedView');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const selectAll = document.getElementById('selectAll');

    let isGroupedView = false;

    function updateToggleButton() {
        toggleViewBtn.textContent = isGroupedView ? 'عرض كامل' : 'عرض مجمّع';
    }

    function toggleView() {
        isGroupedView = !isGroupedView;

        fullTableContainer.classList.toggle('hidden', isGroupedView);
        groupedView.classList.toggle('hidden', !isGroupedView);

        updateToggleButton();
        updateDeleteBtnVisibility();
    }

    toggleViewBtn.addEventListener('click', toggleView);

    function setupExpandCollapseButtons() {
        document.querySelectorAll('.expand-collapse-btn').forEach(button => {
            button.addEventListener('click', function () {
                const unitsList = this.closest('.property-group').querySelector('.units-list');
                const isExpanded = this.dataset.expanded === 'true';

                if (isExpanded) {
                    unitsList.classList.add('hidden');
                    this.textContent = 'عرض الوحدات';
                    this.dataset.expanded = 'false';
                } else {
                    unitsList.classList.remove('hidden');
                    this.textContent = 'إخفاء الوحدات';
                    this.dataset.expanded = 'true';
                }

                setTimeout(updateDeleteBtnVisibility, 100);
            });
        });
    }

    setupExpandCollapseButtons();

    function getAllVisibleCheckboxes() {
        if (isGroupedView) {
            const visibleCheckboxes = [];
            document.querySelectorAll('.property-group').forEach(group => {
                const unitsList = group.querySelector('.units-list');
                const expandBtn = group.querySelector('.expand-collapse-btn');

                if (expandBtn && expandBtn.dataset.expanded === 'true' && !unitsList.classList.contains('hidden')) {
                    unitsList.querySelectorAll('.rowCheckbox').forEach(cb => visibleCheckboxes.push(cb));
                }
            });
            return visibleCheckboxes;
        } else {
            return Array.from(document.querySelectorAll('#fullTable .rowCheckbox'));
        }
    }

    function updateDeleteBtnVisibility() {
        const visibleCheckboxes = getAllVisibleCheckboxes();
        const anyChecked = visibleCheckboxes.some(cb => cb.checked);
        deleteBtn.classList.toggle('hidden', !anyChecked);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('#fullTable .rowCheckbox').forEach(cb => cb.checked = this.checked);
            updateDeleteBtnVisibility();
        });
    }

    function setupGroupedSelectAll() {
        document.querySelectorAll('.selectAllGrouped').forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function () {
                const container = this.closest('.units-list');
                container.querySelectorAll('.grouped-checkbox').forEach(cb => cb.checked = this.checked);
                updateDeleteBtnVisibility();
            });
        });
    }

    setupGroupedSelectAll();

    function setupCheckboxListeners() {
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('rowCheckbox')) {
                updateDeleteBtnVisibility();

                if (e.target.classList.contains('grouped-checkbox')) {
                    const container = e.target.closest('.units-list');
                    const groupCheckbox = container.querySelector('.selectAllGrouped');
                    const allGroupCheckboxes = container.querySelectorAll('.grouped-checkbox');
                    const checkedCount = Array.from(allGroupCheckboxes).filter(cb => cb.checked).length;

                    groupCheckbox.checked = checkedCount === allGroupCheckboxes.length;
                    groupCheckbox.indeterminate = checkedCount > 0 && checkedCount < allGroupCheckboxes.length;
                }
            }
        });
    }

    setupCheckboxListeners();

    deleteBtn.addEventListener('click', function () {
        const selectedCheckboxes = getAllVisibleCheckboxes().filter(cb => cb.checked);
        const selectedIds = selectedCheckboxes.map(cb => cb.value);

        if (selectedIds.length === 0) {
            showToast('لم يتم تحديد أي وحدات', 'error');
            return;
        }

        if (!confirm(`هل أنت متأكد من حذف ${selectedIds.length} وحدة؟`)) return;

        deleteBtn.disabled = true;
        deleteBtn.textContent = 'جاري الحذف...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/units/bulk-delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(res => {
            if (!res.ok) throw new Error('فشل الاتصال بالخادم.');
            return res.json();
        })
        .then(data => {
            if (data.success) {
                selectedIds.forEach(id => {
                    document.querySelectorAll(`tr[data-unit-id="${id}"]`).forEach(row => row.remove());
                });

                showToast(data.success, 'success');

                if (selectAll) selectAll.checked = false;
                document.querySelectorAll('.selectAllGrouped').forEach(cb => {
                    cb.checked = false;
                    cb.indeterminate = false;
                });
            } else {
                showToast(data.error || 'فشل الحذف', 'error');
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            showToast(err.message || 'حدث خطأ أثناء الحذف', 'error');
        })
        .finally(() => {
            deleteBtn.disabled = false;
            deleteBtn.textContent = '🗑 حذف المحدد';
            updateDeleteBtnVisibility();
        });
    });

    function stripHTML(str) {
        return str.replace(/<\/?[^>]+(>|$)/g, '');
    }

    function isNumericField(field) {
        return ['bedrooms', 'bathrooms', 'area', 'floor_number'].includes(field);
    }

    function validateInput(field, value) {
        if (value === '') return 'القيمة لا يمكن أن تكون فارغة';
        if (isNumericField(field) && (isNaN(value) || Number(value) < 0)) {
            return 'القيمة يجب أن تكون رقمًا موجبًا';
        }
        return null;
    }

    function setupEditableFields() {
        document.querySelectorAll('.editable').forEach(el => {
            el.dataset.old = el.innerText.trim();

            el.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    el.blur();
                }
            });

            el.addEventListener('blur', function () {
                const unitId = this.dataset.id;
                const field = this.dataset.field;
                let newValue = stripHTML(this.innerText.trim());
                const oldValue = this.dataset.old;

                if (newValue === oldValue) return;

                const validationError = validateInput(field, newValue);
                if (validationError) {
                    showToast(validationError, 'error');
                    this.innerText = oldValue;
                    return;
                }

                this.setAttribute('contenteditable', 'false');
                this.style.backgroundColor = '#fef08a';

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/units/${unitId}/update-field`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ field, value: newValue })
                })
                .then(res => {
                    if (!res.ok) throw new Error('فشل الاستجابة من الخادم');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        this.dataset.old = newValue;
                        showToast(data.success, 'success');
                    } else {
                        this.innerText = oldValue;
                        showToast(data.error || 'فشل الحفظ', 'error');
                    }
                })
                .catch(err => {
                    console.error('Update error:', err);
                    this.innerText = oldValue;
                    showToast('خطأ في الاتصال بالخادم', 'error');
                })
                .finally(() => {
                    this.setAttribute('contenteditable', 'true');
                    this.style.backgroundColor = '';
                });
            });
        });
    }

    function setupEditableSelects() {
        document.querySelectorAll('.editable-select').forEach(select => {
            select.dataset.old = select.value;

            select.addEventListener('change', function () {
                const unitId = this.dataset.id;
                const field = this.dataset.field;
                const newValue = this.value;
                const oldValue = this.dataset.old;

                if (newValue === oldValue) return;

                this.disabled = true;
                this.style.backgroundColor = '#fef08a';

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/units/${unitId}/update-field`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ field, value: newValue })
                })
                .then(res => {
                    if (!res.ok) throw new Error('فشل الاستجابة من الخادم');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        this.dataset.old = newValue;
                        showToast(data.success, 'success');
                    } else {
                        this.value = oldValue;
                        showToast(data.error || 'فشل الحفظ', 'error');
                    }
                })
                .catch(err => {
                    console.error('Update error:', err);
                    this.value = oldValue;
                    showToast('خطأ في الاتصال بالخادم', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                    this.style.backgroundColor = '';
                });
            });
        });
    }

    function showToast(message, type = 'success') {
        const colors = {
            success: {
                bg: 'bg-green-100',
                border: 'border-green-500',
                text: 'text-green-800'
            },
            error: {
                bg: 'bg-red-100',
                border: 'border-red-500',
                text: 'text-red-800'
            }
        };

        const toast = document.createElement('div');
        toast.className = `
            fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2
            z-50 w-[80%] md:w-[400px]
            ${colors[type].bg} ${colors[type].border} ${colors[type].text}
            px-6 py-4 rounded-xl shadow-lg text-center text-lg font-semibold
            animate-fade-in
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('animate-fade-out');
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }

    window.showToast = showToast;

    updateToggleButton();
    updateDeleteBtnVisibility();
    setupEditableFields();
    setupEditableSelects();
});
