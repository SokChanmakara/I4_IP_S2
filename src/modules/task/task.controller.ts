import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  UsePipes,
  ValidationPipe,
} from '@nestjs/common';
import { TaskService } from './task.service';
import { CreateTaskDto } from './dto/create-task.dto';

@Controller('tasks')
export class TasksController {
  constructor(private readonly taskService: TaskService) {}

  @Get()
  getAll() {
    return this.taskService.getAllTasks();
  }

  @Get(':id')
  getOne(@Param('id') id: string) {
    return this.taskService.getTask(Number(id));
  }

  @Post()
  @UsePipes(new ValidationPipe({whitelist: true}))
  async create(@Body() createTaskDto: CreateTaskDto) {
    const task = await this.taskService.createTask(createTaskDto);
    this.taskService.create(createTaskDto); // Trigger notification
    return task;
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() body: any) {
    return this.taskService.updateTask(Number(id), body);
  }

  @Delete(':id')
  delete(@Param('id') id: string) {
    return this.taskService.deleteTask(Number(id));
  }

  @Post('test-notification')
  testNotification(@Body() body: { title: string }) {
    this.taskService.create(body);
    return { message: 'Notification test completed', title: body.title };
  }
}
