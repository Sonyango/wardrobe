<script setup>
import GuestLayout from '../components/GuestLayout.vue';
</script>

<template>
    <GuestLayout>
        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign up for an account</h2>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-3" action="#" method="POST">
                    <div class="flex justify-between">
                        <div>
                            <label for="fname" class="block text-sm/6 font-medium text-gray-900">First Name</label>
                            <div class="mr-2">
                                <input type="text" name="fname" id="fname" autocomplete="fname" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div>
                            <label for="lname" class="block text-sm/6 font-medium text-gray-900 ml-3">Last Name</label>
                            <div class="ml-2">
                                <input type="text" name="lname" id="lname" autocomplete="lname" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div>
                            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                            <div class="mr-2">
                                <input type="email" name="email" id="email" autocomplete="email" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm/6 font-medium text-gray-900 ml-3">Phone</label>
                            <div class="ml-2">
                                <input type="tel" name="phone" id="phone" autocomplete="phone" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div>
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                            <div class="mr-2">
                                <input type="password" name="password" id="password" autocomplete="password" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div>
                            <label for="password-confirmation" class="block text-sm/6 font-medium text-gray-900 ml-3">Confirm Password</label>
                            <div class="ml-2">
                                <input type="password" name="password-confirmation" id="password-confirmation" autocomplete="password-confirmation" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign-Up</button>
                    </div>
                </form>

                <p class="mt-5 text-center text-sm/6 text-gray-500">
                    Already have an account?
                    {{ ' ' }}
                    <RouterLink :to="{ name: 'login' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Login</RouterLink>
                </p>
            </div>
    </GuestLayout>
</template>

<style scoped></style>