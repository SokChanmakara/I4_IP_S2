import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
} from '@nestjs/common';
import { TaskService } from './task.service';

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
  create(@Body() body: any) {
    return this.taskService.createTask(body);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() body: any) {
    return this.taskService.updateTask(Number(id), body);
  }

  @Delete(':id')
  delete(@Param('id') id: string) {
    return this.taskService.deleteTask(Number(id));
  }
}
