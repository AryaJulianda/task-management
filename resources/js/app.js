import "./bootstrap";
import "bootstrap";
import { Modal } from "bootstrap";
import Choices from "choices.js";
import "choices.js/public/assets/styles/choices.css";

const deleteUserModal = document.getElementById("deleteUserModal");
const roleForms = document.querySelectorAll(".user-role-form");
const confirmActionModal = document.getElementById("confirmActionModal");
const memberSelects = document.querySelectorAll(".js-member-select");
const assigneeSelects = document.querySelectorAll(".js-assignee-select");

if (deleteUserModal) {
    deleteUserModal.addEventListener("show.bs.modal", (event) => {
        const trigger = event.relatedTarget;

        if (!trigger) {
            return;
        }

        const userId = trigger.getAttribute("data-user-id");
        const userName = trigger.getAttribute("data-user-name");
        const nameTarget = deleteUserModal.querySelector("[data-user-name]");
        const passwordInput = deleteUserModal.querySelector(
            'input[name="admin_password"]',
        );
        const userIdInput = deleteUserModal.querySelector(
            'input[name="user_id"]',
        );

        if (nameTarget) {
            nameTarget.textContent = userName || "";
        }

        if (userIdInput) {
            userIdInput.value = userId || "";
        }

        if (passwordInput) {
            passwordInput.value = "";
            passwordInput.focus();
        }
    });
}

if (roleForms.length > 0) {
    roleForms.forEach((form) => {
        const select = form.querySelector('select[name="role"]');
        const button = form.querySelector('button[type="submit"]');
        const initialRole = form.getAttribute("data-initial-role");

        if (!select || !button || !initialRole) {
            return;
        }

        const syncButton = () => {
            button.disabled = select.value === initialRole;
        };

        syncButton();
        select.addEventListener("change", syncButton);
    });
}

if (memberSelects.length > 0) {
    memberSelects.forEach((select) => {
        new Choices(select, {
            allowHTML: false,
            searchEnabled: true,
            itemSelectText: "",
            shouldSort: false,
            placeholder: true,
            placeholderValue: "Cari user...",
        });
    });
}

if (assigneeSelects.length > 0) {
    assigneeSelects.forEach((select) => {
        const manager = select.closest("[data-assignee-manager]");
        if (!manager) {
            return;
        }

        const addButton = manager.querySelector("[data-assignee-add]");
        const list = manager.querySelector("[data-assignee-list]");
        const emptyMessage = manager.querySelector("[data-assignee-empty]");
        const optionMap = new Map(
            Array.from(select.options)
                .filter((option) => option.value)
                .map((option) => [option.value, option.text]),
        );
        const assigned = new Set(
            Array.from(
                list?.querySelectorAll("[data-assignee-item]") || [],
            ).map((item) => item.getAttribute("data-user-id") || ""),
        );
        const choices = new Choices(select, {
            allowHTML: false,
            searchEnabled: true,
            itemSelectText: "",
            shouldSort: false,
            placeholder: true,
            placeholderValue: "Cari user...",
        });

        const rebuildChoices = () => {
            const options = [
                {
                    value: "",
                    label: "Cari user...",
                    disabled: true,
                    selected: true,
                },
            ];

            optionMap.forEach((label, value) => {
                if (!assigned.has(value)) {
                    options.push({ value, label });
                }
            });

            choices.setChoices(options, "value", "label", true);
        };

        const syncEmpty = () => {
            if (!emptyMessage) {
                return;
            }

            const hasItems = list?.querySelector("[data-assignee-item]");
            emptyMessage.classList.toggle("d-none", Boolean(hasItems));
        };

        const addAssignee = () => {
            if (!list) {
                return;
            }

            const selectedValue = select.value;
            if (!selectedValue) {
                return;
            }

            if (assigned.has(selectedValue)) {
                return;
            }

            const label = optionMap.get(selectedValue) || "User";

            const item = document.createElement("div");
            item.className = "d-flex align-items-center gap-2 mb-2";
            item.dataset.assigneeItem = "true";
            item.dataset.userId = selectedValue;

            const badge = document.createElement("span");
            badge.className = "badge text-bg-light";
            badge.textContent = label;

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.className =
                "btn btn-outline-danger btn-sm assignee-remove-btn ms-auto";
            removeButton.dataset.assigneeRemove = "true";
            removeButton.textContent = "Remove";

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "assignees[]";
            input.value = selectedValue;

            item.appendChild(badge);
            item.appendChild(removeButton);
            item.appendChild(input);
            list.appendChild(item);

            assigned.add(selectedValue);
            rebuildChoices();

            syncEmpty();
            choices.removeActiveItems();
        };

        addButton?.addEventListener("click", addAssignee);

        list?.addEventListener("click", (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            if (!target.hasAttribute("data-assignee-remove")) {
                return;
            }

            const item = target.closest("[data-assignee-item]");
            if (item) {
                const userId = item.getAttribute("data-user-id");
                if (userId) {
                    assigned.delete(userId);
                }
                item.remove();
                rebuildChoices();
            }
            syncEmpty();
        });

        rebuildChoices();
        syncEmpty();
    });
}

if (confirmActionModal) {
    let pendingForm = null;
    const confirmModal = new Modal(confirmActionModal);
    const messageTarget = confirmActionModal.querySelector(
        "[data-confirm-message]",
    );
    const confirmButton = confirmActionModal.querySelector(
        "[data-confirm-submit]",
    );

    document.addEventListener("submit", (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        if (!form.hasAttribute("data-confirm")) {
            return;
        }

        event.preventDefault();
        pendingForm = form;

        const message = form.getAttribute("data-confirm-message");
        if (messageTarget) {
            messageTarget.textContent =
                message || "Kamu yakin ingin melanjutkan?";
        }

        confirmModal.show();
    });

    if (confirmButton) {
        confirmButton.addEventListener("click", () => {
            if (pendingForm) {
                pendingForm.submit();
                pendingForm = null;
            }
        });
    }

    confirmActionModal.addEventListener("hidden.bs.modal", () => {
        pendingForm = null;
    });
}
