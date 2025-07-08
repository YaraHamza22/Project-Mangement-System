@component('mail::message')
# مهمة جديدة تم تعيينها إليك

مرحباً {{ $assignee->name }},

لقد تم تعيين مهمة جديدة لك ضمن المشروع.

---

عنوان المهمة: {{ $task->title }}

الوصف:
{{ $task->description ?? 'لا يوجد وصف' }}

تاريخ الاستحقاق:
{{ \Carbon\Carbon::parse($due_date)->translatedFormat('d M Y') }}

---

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
عرض المهمة
@endcomponent

شكراً لك على جهودك المستمرة.

مع تحيات فريق العمل.
@endcomponent
